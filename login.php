<?php
$page_title = "Login";
$current_page = 'login';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

// Check error/success
$error_message = isset($_GET['error']) ? $_GET['error'] : '';
$success_message = isset($_GET['success']) ? $_GET['success'] : '';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Login</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form id="loginForm" action="verify.php" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="favoriteSong" class="form-label">Favorite Miku Song (Optional)</label>
                            <input type="text" class="form-control" id="favoriteSong" name="favoriteSong">
                        </div>
                        
                        <!-- CAPTCHA -->
                        <div class="mb-3">
                            <div class="alert alert-info">
                                <strong>Custom CAPTCHA</strong>
                                <p class="mb-0">Verify Human, totally not stealing your images</p>
                            </div>
                            <button type="button" id="startCaptchaBtn" class="btn btn-outline-primary w-100">Start CAPTCHA</button>
                        </div>
                        
                        <!-- track captcha completion -->
                        <input type="hidden" id="captchaCompleted" name="captchaCompleted" value="0">
                        
                        <div class="text-center">
                            <button id="loginButton" type="submit" class="btn btn-primary" disabled>Login</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">New user? <a href="register.php">Register</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CAPTCHA Modal -->
<div class="modal fade" id="captchaModal" tabindex="-1" aria-labelledby="captchaModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="captchaModalLabel">Gesture CAPTCHA Verification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <p>Complete all hand gestures to verify you're human:</p>
                    
                    <div class="row">
                        <div class="col-md-7">
                            <div class="camera-container position-relative mb-3" style="height: 300px; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                                <video id="video" style="display: none;"></video>
                                <canvas id="canvas" class="w-100 h-100"></canvas>
                                <div id="overlay" class="position-absolute top-0 start-0 w-100 text-center p-2 bg-dark bg-opacity-50 text-white">
                                    Perform the required gestures
                                </div>
                                <div id="loading" class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center">


                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="gesture-list d-flex flex-column gap-3 justify-content-center h-100">
                                <div id="gesture-open-hand" class="text-center p-3 border rounded active">
                                    <span class="gesture-emoji display-1">🖐</span>
                                </div>
                                
                                <div id="gesture-thumbs-up" class="text-center p-3 border rounded">
                                    <span class="gesture-emoji display-1">👍</span>
                                </div>
                                
                                <div id="gesture-peace" class="text-center p-3 border rounded">
                                    <span class="gesture-emoji display-1">✌️</span>
                                </div>
                                
                                <div id="gesture-rock-on" class="text-center p-3 border rounded">
                                    <span class="gesture-emoji display-1">🤘</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bar -->
                    <div class="progress mb-2 mt-3">
                        <div id="captcha-progress" class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                    <p id="captcha-progress-text" class="text-center">0/4 gestures completed</p>
                </div>
                
                <!-- Completion -->
                <div id="captcha-completion" class="alert alert-success mt-3" style="display: none;">
                    <h5 class="alert-heading">Verified!</h5>
                    <p>All gestures completed successfully. You can now log in.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="completeCaptchaBtn" class="btn btn-primary" disabled>Complete Verification</button>
            </div>
        </div>
    </div>
</div>

<!-- Celebration effect -->
<div id="celebration" class="position-fixed w-100 h-100 top-0 start-0 pointer-events-none" style="z-index: 1050; overflow: hidden; display: none;"></div>

<!-- TensorFlow -->
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@3.11.0"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/hand-pose-detection@2.0.0"></script>

<script>

let detector;
let video;
let canvas;
let ctx;
let currentStep = 0;
let completedSteps = [];
let animationFrameId;
let cooldown = false;
let captchaModal;
let isDetectionRunning = false;
let modelLoaded = false;

// gesture
const steps = [
    { name: 'open_hand', emoji: '🖐', elementId: 'gesture-open-hand' },
    { name: 'thumbs_up', emoji: '👍', elementId: 'gesture-thumbs-up' },
    { name: 'peace', emoji: '✌️', elementId: 'gesture-peace' },
    { name: 'rock_on', emoji: '🤘', elementId: 'gesture-rock-on' }
];

// stole these code from the internet
document.addEventListener('DOMContentLoaded', function() {
    captchaModal = new bootstrap.Modal(document.getElementById('captchaModal'));
    document.getElementById('startCaptchaBtn').addEventListener('click', function() {
        captchaModal.show();
        
        setTimeout(() => {
            if (!isDetectionRunning) {
                resetCaptcha();
                init();
                isDetectionRunning = true;
            }
        }, 300);
    });
    
    document.getElementById('captchaModal').addEventListener('hidden.bs.modal', function() {
        stopDetection();
    });
    
    document.getElementById('completeCaptchaBtn').addEventListener('click', function() {
        document.getElementById('captchaCompleted').value = '1';
        document.getElementById('loginButton').disabled = false;
        captchaModal.hide();
        
        const successMsg = document.createElement('div');
        successMsg.className = 'alert alert-success mt-3';
        successMsg.innerHTML = '<strong>CAPTCHA Verified!</strong> You can now log in.';
        
        document.getElementById('loginButton').parentNode.insertBefore(successMsg, document.getElementById('loginButton'));
        
        document.getElementById('startCaptchaBtn').classList.remove('btn-outline-primary');
        document.getElementById('startCaptchaBtn').classList.add('btn-success');
        document.getElementById('startCaptchaBtn').innerHTML = 'Verification Complete';
        document.getElementById('startCaptchaBtn').disabled = true;
    });
    
    preloadModels();
});

async function preloadModels() {
    try {
        await Promise.all([
            loadScript('https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js'),
            loadScript('https://cdn.jsdelivr.net/npm/@tensorflow-models/hand-pose-detection/dist/hand-pose-detection.js')
        ]);
        
        modelLoaded = true;
        console.log('Models preloaded successfully');
    } catch (error) {
        console.error('Error preloading models:', error);
    }
}

function loadScript(url) {
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = url;
        script.onload = resolve;
        script.onerror = () => reject(new Error(`Failed to load model: ${url}`));
        document.body.appendChild(script);
    });
}

function stopDetection() {
    if (animationFrameId) {
        cancelAnimationFrame(animationFrameId);
        animationFrameId = null;
    }
    
    if (video && video.srcObject) {
        const tracks = video.srcObject.getTracks();
        tracks.forEach(track => track.stop());
        video.srcObject = null;
    }
    
    isDetectionRunning = false;
}

function resetCaptcha() {
    currentStep = 0;
    completedSteps = [];
    cooldown = false;
    
    document.getElementById('captcha-progress').style.width = '0%';
    document.getElementById('captcha-progress-text').textContent = '0/4 gestures completed';
    document.getElementById('captcha-completion').style.display = 'none';
    document.getElementById('completeCaptchaBtn').disabled = true;
    
    steps.forEach((step, index) => {
        const el = document.getElementById(step.elementId);
        el.classList.remove('active', 'border-primary', 'bg-success', 'bg-opacity-25', 'text-success');
        if (index === 0) {
            el.classList.add('active', 'border-primary');
        }
    });
}

async function init() {
    try {
        video = document.getElementById('video');
        canvas = document.getElementById('canvas');
        ctx = canvas.getContext('2d');
        
        const loadingElement = document.getElementById('loading');
        loadingElement.style.display = 'flex';
        
        updateLoadingProgress(10, 'Requesting camera permission...');
        
        try {
            if (modelLoaded) {
                updateLoadingProgress(60, 'Models already loaded');
            } else {
                updateLoadingProgress(30, 'Loading hand detection model...');
                
                await Promise.all([
                    loadScript('https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js'),
                    loadScript('https://cdn.jsdelivr.net/npm/@tensorflow-models/hand-pose-detection/dist/hand-pose-detection.js')
                ]);
                
                updateLoadingProgress(60, 'Models loaded');
            }
            
            updateLoadingProgress(70, 'Initializing detector...');
            const model = handPoseDetection.SupportedModels.MediaPipeHands;
            const detectorConfig = {
                runtime: 'mediapipe',
                solutionPath: 'https://cdn.jsdelivr.net/npm/@mediapipe/hands',
                modelType: 'lite',
                maxHands: 1
            };
            
            detector = await handPoseDetection.createDetector(model, detectorConfig);
            
            updateLoadingProgress(80, 'Accessing camera...');
            const constraints = {
                video: {
                    width: { ideal: 640 },
                    height: { ideal: 480 },
                    facingMode: 'user'
                },
                audio: false
            };
            
            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            
            video.srcObject = stream;
            video.setAttribute('playsinline', '');
            video.setAttribute('autoplay', '');
            
            await new Promise(resolve => {
                video.onloadeddata = resolve;
            });
            
            await video.play();
            
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            updateLoadingProgress(100, 'Ready!');
            setTimeout(() => {
                const loadingElement = document.getElementById('loading');
                if (loadingElement) {
                    loadingElement.style.display = 'none';
                }
            }, 500);
            
            detectHands();
            updateInstructions();
            
        } catch (error) {
            console.error('Initialization error:', error);
            showError('Could not initialize: ' + error.message);
        }
        
    } catch (error) {
        console.error('Setup error:', error);
        showError('Could not set up camera: ' + error.message);
    }
}

function updateLoadingProgress(progress, message) {
    const progressBar = document.getElementById('loading-progress');
    const loadingMessage = document.getElementById('loading-message');
    
    if (progressBar && loadingMessage) {
        progressBar.style.width = `${progress}%`;
        loadingMessage.textContent = message;
        
        if (progress >= 100) {
            setTimeout(() => {
                const loadingElement = document.getElementById('loading');
                if (loadingElement) {
                    loadingElement.style.display = 'none';
                }
            }, 500);
        }
    }
}


function showError(message) {
    const loadingElement = document.getElementById('loading');
    loadingElement.innerHTML = `
        <div class="error-message p-3 text-center">
            <p>${message}</p>
            <p>Troubleshooting tips:</p>
            <ul class="text-start mb-3 small">
                <li>Make sure your camera is not being used by another application</li>
                <li>Check browser permissions (click the camera icon in the address bar)</li>
                <li>Try using Chrome, Firefox, or Edge for better compatibility</li>
                <li>Make sure you're on a secure connection (https)</li>
            </ul>
            <button class="btn btn-primary" onclick="location.reload()">Try Again</button>
        </div>
    `;
}

async function detectHands() {
    if (video.readyState === 4) {
        try {
            const hands = await detector.estimateHands(video);
            
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            if (hands.length > 0) {
                const keypoints = hands[0].keypoints;
                
                drawHandSkeleton(keypoints, ctx);
                
                if (!cooldown && currentStep < steps.length) {
                    const currentGesture = steps[currentStep].name;
                    if (detectGesture(currentGesture, keypoints)) {
                        handleGestureDetected();
                    }
                }
            }
        } catch (error) {
            console.error('Error in hand detection:', error);
        }
    }
    
    animationFrameId = requestAnimationFrame(detectHands);
}

function drawHandSkeleton(keypoints, ctx) {
    const connections = [
        [0, 1], [1, 2], [2, 3], [3, 4],
        [0, 5], [5, 6], [6, 7], [7, 8],
        [5, 9], [9, 10], [10, 11], [11, 12],
        [9, 13], [13, 14], [14, 15], [15, 16],
        [13, 17], [17, 18], [18, 19], [19, 20],
        [0, 17],
    ];
    
    connections.forEach(([start, end]) => {
        drawPath([keypoints[start], keypoints[end]], ctx, {
            color: 'cyan',
            lineWidth: 4
        });
    });
    
    keypoints.forEach(point => {
        ctx.beginPath();
        ctx.arc(point.x, point.y, 5, 0, 2 * Math.PI);
        ctx.fillStyle = 'yellow';
        ctx.fill();
    });
}

function drawPath(points, ctx, style = { color: 'cyan', lineWidth: 4 }) {
    const { color, lineWidth } = style;
    ctx.beginPath();
    ctx.moveTo(points[0].x, points[0].y);
    ctx.lineTo(points[1].x, points[1].y);
    ctx.strokeStyle = color;
    ctx.lineWidth = lineWidth;
    ctx.stroke();
}

function detectGesture(gesture, keypoints) {
    switch (gesture) {
        case 'open_hand':
            return detectOpenHandGesture(keypoints);
        case 'thumbs_up':
            return detectThumbsUpGesture(keypoints);
        case 'peace':
            return detectVictoryGesture(keypoints);
        case 'rock_on':
            return detectRockOnGesture(keypoints);
        default:
            return false;
    }
}

function detectOpenHandGesture(keypoints) {
    const threshold = 20;
    return (
        keypoints[8].y < keypoints[6].y - threshold &&
        keypoints[12].y < keypoints[10].y - threshold &&
        keypoints[16].y < keypoints[14].y - threshold &&
        keypoints[20].y < keypoints[18].y - threshold
    );
}

function detectThumbsUpGesture(keypoints) {
    const threshold = 20;
    return (
        keypoints[4].y < keypoints[3].y - threshold &&
        keypoints[8].y > keypoints[6].y - 10 &&
        keypoints[12].y > keypoints[10].y - 10 &&
        keypoints[16].y > keypoints[14].y - 10 &&
        keypoints[20].y > keypoints[18].y - 10
    );
}

function detectVictoryGesture(keypoints) {
    const threshold = 20;
    return (
        keypoints[8].y < keypoints[6].y - threshold &&
        keypoints[12].y < keypoints[10].y - threshold &&
        keypoints[16].y > keypoints[14].y - 10 &&
        keypoints[20].y > keypoints[18].y - 10
    );
}

function detectRockOnGesture(keypoints) {
    const threshold = 15;
    const isIndexExtended = keypoints[8].y < keypoints[6].y - threshold;
    const isPinkyExtended = keypoints[20].y < keypoints[19].y - threshold;
    
    const isMiddleCurled = keypoints[12].y > keypoints[10].y;
    const isRingCurled = keypoints[16].y > keypoints[14].y;
    
    return isIndexExtended && isPinkyExtended && isMiddleCurled && isRingCurled;
}

function handleGestureDetected() {
    if (!completedSteps.includes(currentStep)) {
        completedSteps.push(currentStep);
        
        document.getElementById(steps[currentStep].elementId).classList.remove('active');
        document.getElementById(steps[currentStep].elementId).classList.add('bg-success', 'bg-opacity-25', 'text-success');
        
        playSuccessSound();
        showCelebration(steps[currentStep].emoji);
        
        cooldown = true;
        setTimeout(() => {
            cooldown = false;
        }, 2000);
        
        if (currentStep < steps.length - 1) {
            currentStep++;
            document.getElementById(steps[currentStep].elementId).classList.add('active', 'border-primary');
            updateInstructions();
        } else {
            completeAllGestures();
        }
        
        updateProgress();
    }
}

function updateInstructions() {
    const overlay = document.getElementById('overlay');
    if (currentStep < steps.length) {
        const gestureName = steps[currentStep].name.replace('_', ' ');
        overlay.textContent = `Perform "${gestureName}" gesture ${steps[currentStep].emoji}`;
    } else {
        overlay.textContent = "Complete!";
    }
}

function updateProgress() {
    const totalSteps = steps.length;
    const completedCount = completedSteps.length;
    const progressPercent = (completedCount / totalSteps) * 100;
    
    document.getElementById('captcha-progress').style.width = `${progressPercent}%`;
    document.getElementById('captcha-progress-text').textContent = `${completedCount}/${totalSteps} gestures completed`;
}

function playSuccessSound() {
    const audio = new Audio('data:audio/mp3;base64,SUQzBAAAAAAAI1RTU0UAAAAPAAADTGF2ZjU4Ljc2LjEwMAAAAAAAAAAAAAAA/+M4wAAAAAAAAAAAAEluZm8AAAAPAAAAAwAABPAAPj4+Pj4+Pj4+Pj4+Pj5eXl5eXl5eXl5eXl5eXn5+fn5+fn5+fn5+fn5+nZ2dnZ2dnZ2dnZ2dnZ29vb29vb29vb29vb29vf7+/v7+/v7+/v7+/v7+AAAA//sQZAAP8AAAaQAAAAgAAA0gAAABAAABpAAAACAAADSAAAAETEFNRTMuMTAwVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVX/+xBkKg/wAABpAAAACAAADSAAAAEAAAGkAAAAIAAANIAAAARVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVf/7EGTWD/AAAGkAAAAIAAANIAAAAQAAAaQAAAAgAAA0gAAABFVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVQ==');
    audio.play();
}

// celebration effect
function showCelebration(emoji) {
    const celebrationEl = document.getElementById('celebration');
    celebrationEl.style.display = 'block';
    
    for (let i = 0; i < 10; i++) {
        const emojiEl = document.createElement('div');
        emojiEl.className = 'position-absolute';
        emojiEl.style.fontSize = '24px';
        emojiEl.textContent = emoji;
        
        const randomX = Math.random() * window.innerWidth;
        emojiEl.style.left = `${randomX}px`;
        emojiEl.style.top = '100%';
        
        // Animation
        emojiEl.style.animation = `float-up 2s ease-out forwards`;
        emojiEl.style.animationDelay = `${Math.random() * 0.5}s`;
        
        celebrationEl.appendChild(emojiEl);
        
        setTimeout(() => {
            emojiEl.remove();
        }, 3000);
    }
    
    setTimeout(() => {
        celebrationEl.style.display = 'none';
    }, 3500);
}

function completeAllGestures() {
    document.getElementById('overlay').textContent = "All gestures completed! 🎉";
    document.getElementById('captcha-completion').style.display = 'block';
    document.getElementById('completeCaptchaBtn').disabled = false;
    document.getElementById('captcha-completion').scrollIntoView({ behavior: 'smooth' });
}

// CSS animations
document.head.insertAdjacentHTML('beforeend', `
<style>
    .active {
        border: 3px solid #0d6efd !important;
        background-color: rgba(13, 110, 253, 0.1);
    }
    
    .gesture-emoji {
        font-size: 4rem;
    }
    
    @keyframes float-up {
        0% {
            transform: translateY(0) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(-500px) rotate(360deg);
            opacity: 0;
        }
    }
</style>
`);
</script>

<?php require_once 'includes/footer.php'; ?>