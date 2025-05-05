<?php
header('Content-Type: application/json');
require_once '../config.php';

// Function to generate unique ID
function generateUniqueId() {
    return uniqid() . rand(1000, 9999);
}

// Get user progress data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    
    try {
        // Get completed courses count
        $completed_courses = $conn->prepare("SELECT COUNT(*) FROM course_progress WHERE user_id = ? AND completed = 1");
        $completed_courses->execute([$user_id]);
        $completed_count = $completed_courses->fetchColumn();

        // Get average quiz score
        $avg_score = $conn->prepare("
            SELECT AVG(score/total_questions * 100) 
            FROM quiz_scores 
            WHERE user_id = ?
        ");
        $avg_score->execute([$user_id]);
        $average_score = round($avg_score->fetchColumn() ?? 0, 2);

        // Get total time spent
        $time_spent = $conn->prepare("
            SELECT SUM(minutes_spent) 
            FROM learning_time 
            WHERE user_id = ?
        ");
        $time_spent->execute([$user_id]);
        $total_minutes = $time_spent->fetchColumn() ?? 0;

        // Get course progress
        $courses = $conn->prepare("
            SELECT p.id, p.title, p.description, cp.progress
            FROM playlist p
            LEFT JOIN course_progress cp ON p.id = cp.playlist_id AND cp.user_id = ?
            WHERE p.status = 'active'
        ");
        $courses->execute([$user_id]);
        $course_progress = $courses->fetchAll(PDO::FETCH_ASSOC);

        // Get achievements
        $achievements = $conn->prepare("
            SELECT a.id, a.title, a.description, a.icon, 
                   CASE WHEN ua.id IS NOT NULL THEN 1 ELSE 0 END as unlocked
            FROM achievements a
            LEFT JOIN user_achievements ua ON a.id = ua.achievement_id AND ua.user_id = ?
        ");
        $achievements->execute([$user_id]);
        $user_achievements = $achievements->fetchAll(PDO::FETCH_ASSOC);

        // Prepare response
        $response = [
            'completedCourses' => $completed_count,
            'averageScore' => $average_score,
            'timeSpent' => $total_minutes,
            'courses' => $course_progress,
            'achievements' => $user_achievements
        ];

        echo json_encode($response);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

// Update course progress
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = $_SESSION['user_id'];
    
    try {
        if (isset($data['playlist_id']) && isset($data['progress'])) {
            // Check if progress record exists
            $check = $conn->prepare("SELECT id FROM course_progress WHERE user_id = ? AND playlist_id = ?");
            $check->execute([$user_id, $data['playlist_id']]);
            
            if ($check->rowCount() > 0) {
                // Update existing progress
                $update = $conn->prepare("
                    UPDATE course_progress 
                    SET progress = ?, last_accessed = CURRENT_TIMESTAMP,
                        completed = CASE WHEN ? >= 100 THEN 1 ELSE 0 END
                    WHERE user_id = ? AND playlist_id = ?
                ");
                $update->execute([$data['progress'], $data['progress'], $user_id, $data['playlist_id']]);
            } else {
                // Create new progress record
                $insert = $conn->prepare("
                    INSERT INTO course_progress (id, user_id, playlist_id, progress, completed)
                    VALUES (?, ?, ?, ?, CASE WHEN ? >= 100 THEN 1 ELSE 0 END)
                ");
                $insert->execute([
                    generateUniqueId(),
                    $user_id,
                    $data['playlist_id'],
                    $data['progress'],
                    $data['progress']
                ]);
            }
            
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?> 