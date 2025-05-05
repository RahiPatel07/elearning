class QuizSystem {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        this.questions = [];
        this.currentQuestion = 0;
        this.score = 0;
        this.quizData = null;
    }

    async loadQuiz(quizId) {
        try {
            // In a real implementation, this would fetch from your backend
            this.quizData = {
                id: quizId,
                title: "Sample Quiz",
                questions: [
                    {
                        id: 1,
                        question: "What is HTML?",
                        options: [
                            "Hyper Text Markup Language",
                            "High Tech Modern Language",
                            "Hyper Transfer Markup Language",
                            "Home Tool Markup Language"
                        ],
                        correctAnswer: 0,
                        explanation: "HTML stands for Hyper Text Markup Language, which is the standard markup language for creating web pages."
                    },
                    // Add more questions here
                ]
            };
            this.renderQuiz();
        } catch (error) {
            console.error("Error loading quiz:", error);
        }
    }

    renderQuiz() {
        if (!this.quizData) return;

        const quizHTML = `
            <div class="quiz-container">
                <h2>${this.quizData.title}</h2>
                <div class="progress-bar">
                    <div class="progress" style="width: ${(this.currentQuestion / this.quizData.questions.length) * 100}%"></div>
                </div>
                <div class="question-container">
                    ${this.renderQuestion()}
                </div>
                <div class="quiz-controls">
                    <button class="btn btn-primary" id="nextQuestion">Next Question</button>
                </div>
            </div>
        `;

        this.container.innerHTML = quizHTML;
        this.setupEventListeners();
    }

    renderQuestion() {
        const question = this.quizData.questions[this.currentQuestion];
        return `
            <div class="question">
                <h3>Question ${this.currentQuestion + 1}: ${question.question}</h3>
                <div class="options">
                    ${question.options.map((option, index) => `
                        <div class="option" data-index="${index}">
                            <input type="radio" name="answer" id="option${index}" value="${index}">
                            <label for="option${index}">${option}</label>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }

    setupEventListeners() {
        const options = this.container.querySelectorAll('.option');
        options.forEach(option => {
            option.addEventListener('click', () => this.selectAnswer(option));
        });

        const nextButton = this.container.querySelector('#nextQuestion');
        nextButton.addEventListener('click', () => this.nextQuestion());
    }

    selectAnswer(option) {
        const selectedOption = option;
        const options = this.container.querySelectorAll('.option');
        
        options.forEach(opt => opt.classList.remove('selected', 'correct', 'incorrect'));
        selectedOption.classList.add('selected');

        const selectedIndex = parseInt(selectedOption.dataset.index);
        const correctIndex = this.quizData.questions[this.currentQuestion].correctAnswer;

        if (selectedIndex === correctIndex) {
            selectedOption.classList.add('correct');
            this.score++;
        } else {
            selectedOption.classList.add('incorrect');
            options[correctIndex].classList.add('correct');
        }

        this.showExplanation();
    }

    showExplanation() {
        const explanation = this.quizData.questions[this.currentQuestion].explanation;
        const explanationDiv = document.createElement('div');
        explanationDiv.className = 'explanation';
        explanationDiv.innerHTML = `<p>${explanation}</p>`;
        this.container.querySelector('.question-container').appendChild(explanationDiv);
    }

    nextQuestion() {
        this.currentQuestion++;
        if (this.currentQuestion < this.quizData.questions.length) {
            this.renderQuiz();
        } else {
            this.showResults();
        }
    }

    showResults() {
        const resultsHTML = `
            <div class="quiz-results">
                <h2>Quiz Complete!</h2>
                <p>Your score: ${this.score} out of ${this.quizData.questions.length}</p>
                <div class="progress-circle" data-progress="${(this.score / this.quizData.questions.length) * 100}">
                    <svg class="progress-circle-svg">
                        <circle class="progress-circle-bg"/>
                        <circle class="progress-circle-fill"/>
                    </svg>
                    <div class="progress-circle-text">${Math.round((this.score / this.quizData.questions.length) * 100)}%</div>
                </div>
                <button class="btn btn-primary" id="retryQuiz">Try Again</button>
            </div>
        `;

        this.container.innerHTML = resultsHTML;
        this.setupResultsEventListeners();
    }

    setupResultsEventListeners() {
        const retryButton = this.container.querySelector('#retryQuiz');
        retryButton.addEventListener('click', () => {
            this.currentQuestion = 0;
            this.score = 0;
            this.renderQuiz();
        });
    }
}

// Initialize quiz system
document.addEventListener('DOMContentLoaded', () => {
    const quizContainer = document.getElementById('quizContainer');
    if (quizContainer) {
        const quiz = new QuizSystem('quizContainer');
        quiz.loadQuiz('sample-quiz');
    }
}); 