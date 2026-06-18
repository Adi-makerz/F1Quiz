// State Variables
let sessionQuestions = [];
let currentQuestionIndex = 0;
let correctAnswers = 0;
let pointsPerQuestion = 10;
let currentDifficulty = 'medium';

// DOM Elements
const startScreen = document.getElementById('start-screen');
const quizScreen = document.getElementById('quiz-screen');
const resultScreen = document.getElementById('result-screen');

const startBtn = document.getElementById('start-btn');
const nextBtn = document.getElementById('next-btn');
const restartBtn = document.getElementById('restart-btn');

const questionText = document.getElementById('question-text');
const optionsContainer = document.getElementById('options-container');
const questionTracker = document.getElementById('question-tracker');
const progressFill = document.getElementById('progress-fill');

const scoreText = document.getElementById('score-text');
const totalText = document.getElementById('total-text');
const pointsText = document.getElementById('points-text');
const feedbackText = document.getElementById('feedback-text');

// Event Listeners
startBtn.addEventListener('click', startQuiz);
nextBtn.addEventListener('click', () => {
    currentQuestionIndex++;
    if (currentQuestionIndex < sessionQuestions.length) {
        loadQuestion();
    } else {
        showResults();
    }
});

restartBtn.addEventListener('click', () => {
    window.location.href = 'dashboard.php';
});

// Utility: Fisher-Yates Shuffle Algorithm
function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
}

// Functions
async function startQuiz() {
    startScreen.classList.remove('active');
    resultScreen.classList.remove('active');
    
    // Add a loading state while fetching from the database
    questionText.textContent = "Loading telemetry data...";
    quizScreen.classList.add('active');

    // Retrieve difficulty and set points per question
    const difficultySelect = document.getElementById('difficulty-select');
    currentDifficulty = difficultySelect.value;
    
    if (currentDifficulty === 'easy') pointsPerQuestion = 10;
    else if (currentDifficulty === 'medium') pointsPerQuestion = 20;
    else if (currentDifficulty === 'hard') pointsPerQuestion = 30;

    try {
        const response = await fetch(`get_questions.php?difficulty=${currentDifficulty}`);
        const data = await response.json();
        
        sessionQuestions = data;
        currentQuestionIndex = 0;
        correctAnswers = 0;

        if (sessionQuestions.length === 0) {
            questionText.textContent = `No telemetry data found for ${currentDifficulty} pace. Admin must add questions.`;
            return;
        }

        loadQuestion();
    } catch (error) {
        console.error("Error fetching questions:", error);
        questionText.textContent = "Telemetry connection lost. Please restart.";
    }
}

function loadQuestion() {
    nextBtn.classList.add('hide');
    optionsContainer.innerHTML = '';
    
    const currentQ = sessionQuestions[currentQuestionIndex];
    questionText.textContent = currentQ.question;
    
    questionTracker.textContent = `Question ${currentQuestionIndex + 1}/${sessionQuestions.length}`;
    progressFill.style.width = `${((currentQuestionIndex + 1) / sessionQuestions.length) * 100}%`;

    let shuffledOptions = [...currentQ.options];
    shuffleArray(shuffledOptions);

    shuffledOptions.forEach(option => {
        const button = document.createElement('button');
        button.textContent = option;
        button.classList.add('option-btn');
        button.addEventListener('click', () => selectAnswer(button, option, currentQ.answer));
        optionsContainer.appendChild(button);
    });
}

function selectAnswer(selectedButton, selectedOption, correctAnswer) {
    const isCorrect = selectedOption === correctAnswer;
    
    if (isCorrect) {
        selectedButton.classList.add('correct');
        correctAnswers++;
    } else {
        selectedButton.classList.add('incorrect');
    }

    // Disable all buttons and highlight the correct answer
    Array.from(optionsContainer.children).forEach(button => {
        button.disabled = true;
        if (button.textContent === correctAnswer) {
            button.classList.add('correct');
        }
    });

    nextBtn.classList.remove('hide');
}

function showResults() {
    quizScreen.classList.remove('active');
    resultScreen.classList.add('active');
    
    // Calculate final points and accuracy
    const finalPoints = correctAnswers * pointsPerQuestion;
    const accuracy = Math.round((correctAnswers / sessionQuestions.length) * 100);

    scoreText.textContent = correctAnswers;
    totalText.textContent = sessionQuestions.length;
    pointsText.textContent = finalPoints;

    // Package the telemetry payload data
    const formData = new FormData();
    formData.append('score', finalPoints);
    formData.append('correct', correctAnswers);
    formData.append('accuracy', accuracy);
    formData.append('difficulty', currentDifficulty);

    // Hit update_score.php which will record both history and points balances
    fetch('update_score.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) 
    .then(data => {
        if(data.success) {
            console.log("Telemetry logs synchronized: " + data.message);
        } else {
            console.error("Telemetry failed to save: " + data.message);
        }
    })
    .catch(error => console.error('Error updating points:', error));

    if (correctAnswers === sessionQuestions.length) {
        feedbackText.textContent = "Flawless victory! A true Grand Slam.";
    } else if (correctAnswers >= sessionQuestions.length * 0.7) {
        feedbackText.textContent = "Solid points finish. You know your stuff!";
    } else {
        feedbackText.textContent = "Tough race. Time to head back to the simulator.";
    }
}