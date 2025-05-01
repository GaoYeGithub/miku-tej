(function oneko() {
    const styleEl = document.createElement('style');
    styleEl.textContent = `
      #oneko {
        pointer-events: auto;
        z-index: 9998;
        cursor: pointer;
      }
      .oneko-speech-bubble {
        position: fixed;
        background: white;
        padding: 8px 12px;
        border-radius: 8px;
        font-family: 'Arial', sans-serif;
        font-size: 14px;
        line-height: 1.2;
        z-index: 9999;
        filter: drop-shadow(0 1px 2px rgba(0,0,0,0.2));
        pointer-events: none;
        white-space: nowrap;
        max-width: 200px;
        box-sizing: border-box;
        border-bottom: 3px solid #0e6dad;
      }
      .oneko-speech-bubble::after {
        content: '';
        position: absolute;
        width: 10px;
        height: 10px;
        background: white;
        transform: rotate(45deg);
        bottom: -5px;
        left: 15px;
        z-index: -1;
      }
      .oneko-speech-bubble span {
        opacity: 0;
        transition: opacity 0.05s;
      }
      .oneko-speech-bubble span.revealed {
        opacity: 1;
      }
      .oneko-speech-bubble span.green {
        color: #27ae60;
      }
      .oneko-speech-bubble span.red {
        color: #ff0000;
      }
      .oneko-speech-bubble span.blue {
        color: #3498db;
      }
      #oneko-control-panel {
        position: fixed;
        width: 250px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        padding: 15px;
        font-family: 'Arial', sans-serif;
        z-index: 9997;
        border: 2px solid #0e6dad;
        display: none;
        top: 20px;
        right: 20px;
      }
      #oneko-control-panel h3 {
        margin: 0 0 10px 0;
        color: #0e6dad;
        font-size: 16px;
        text-align: center;
        border-bottom: 1px solid #eee;
        padding-bottom: 5px;
      }
      .oneko-control-button {
        display: block;
        width: 100%;
        padding: 8px 10px;
        margin: 5px 0;
        background: #f0f8ff;
        border: 1px solid #0e6dad;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 14px;
        text-align: left;
      }
      .oneko-control-button:hover {
        background: #0e6dad;
        color: white;
      }
      .oneko-control-button i {
        margin-right: 8px;
        width: 16px;
        text-align: center;
      }
      .oneko-close-button {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        font-size: 16px;
        cursor: pointer;
        color: #0e6dad;
      }
      .oneko-result {
        margin-top: 10px;
        padding: 10px;
        background: #f7f7f7;
        border-radius: 5px;
        font-size: 13px;
        display: none;
      }
      .oneko-result.active {
        display: block;
      }
    `;
    document.head.appendChild(styleEl);
    
    let nekoEl = document.getElementById('oneko');
    let nekoBubble = document.getElementById('oneko-speech-bubble');
    let controlPanel = document.getElementById('oneko-control-panel');
    let meowAudio = document.getElementById('oneko-meow-audio');
    
    if (nekoEl) {
      nekoEl.remove();
      if (nekoBubble) nekoBubble.remove();
      if (controlPanel) controlPanel.remove();
      if (meowAudio) meowAudio.remove();
      if (window.onekoInterval) {
        clearInterval(window.onekoInterval);
        window.onekoInterval = null;
      }
      if (window.nekoBubbleTimer) {
        clearTimeout(window.nekoBubbleTimer);
        window.nekoBubbleTimer = null;
      }
      if (window.nekoPendingMessages) {
        window.nekoPendingMessages = [];
      }
      return;
    }
  
    meowAudio = document.createElement('audio');
    meowAudio.id = 'oneko-meow-audio';
    meowAudio.src = 'https://hc-cdn.hel1.your-objectstorage.com/s/v3/e1b9161bd16f1775870e343551300831b3f3ebcc_meow_audio.mp4';
    meowAudio.preload = 'auto';
    document.body.appendChild(meowAudio);
    
    nekoEl = document.createElement("div");
    nekoEl.id = "oneko";
    nekoEl.style.width = "32px";
    nekoEl.style.height = "32px";
    nekoEl.style.position = "fixed";
    nekoEl.style.imageRendering = "pixelated";
    nekoEl.style.left = "16px";
    nekoEl.style.top = "16px";
    
    nekoEl.style.backgroundImage = "url('https://hc-cdn.hel1.your-objectstorage.com/s/v3/5cc5be4ed297dcfca99cf02c9bfee6f77c531134_sprite-0004.gif')";
    document.body.appendChild(nekoEl);
  
    nekoBubble = document.createElement("div");
    nekoBubble.id = "oneko-speech-bubble";
    nekoBubble.className = "oneko-speech-bubble";
    nekoBubble.style.display = "none";
    document.body.appendChild(nekoBubble);
    
    controlPanel = document.createElement("div");
    controlPanel.id = "oneko-control-panel";
    controlPanel.innerHTML = `
      <h3>Oneko Control Panel</h3>
      <button class="oneko-close-button">&times;</button>
      <button class="oneko-control-button" id="oneko-weather-button">
        <i>☁️</i> Weather
      </button>
      <div class="oneko-result" id="oneko-weather-result"></div>
      
      <button class="oneko-control-button" id="oneko-time-button">
        <i>🕒</i> Time and Date
      </button>
      <div class="oneko-result" id="oneko-time-result"></div>
      
      <button class="oneko-control-button" id="oneko-motivation-button">
        <i>✨</i> Motivation
      </button>
      <div class="oneko-result" id="oneko-motivation-result"></div>
    `;
    document.body.appendChild(controlPanel);

    const messages = [
        { text: "Oh, hello there!", delay: 5000 },
        { text: "I'm just here hunting mouse!", delay: 8000 },
        { text: "Don't mind me!!", delay: 10000 },
        { text: "Need a break? Me too!", delay: 12000 },
        { text: "Zzz... Oh! I wasn't sleeping!", delay: 7000 },
        { text: "Let's explore this page!", delay: 9000 },
        { text: "I love chasing the mouse!", delay: 11000 },
        { text: "The internet is so fun!", delay: 8000 },
        { text: "Purrrrr... this is nice.", delay: 10000 }
    ];
    
    const clickMessages = [
        "Meow! You found me!",
        "Purrrr~",
        "Meow! That tickles!",
        "I love treats!",
        "Mrow! More pets!"
    ];
    
    const motivationalMessages = [
        "Purr-spective is everything.",
        "You're the cat's paw-jamas! ",
        "Paw-sitively brilliant!",
        "Let's make purr-ogress. ",
        "You're claw-some.",
        "Stay pawsitive! Good things are coming!",
        "Stop stressing meowt.",
        "Stay paw-sitive!",
        "Take a paws and calm down.",
        "You're purr-fect the way you are."
    ];
  
    window.nekoPendingMessages = [];
    window.nekoIsSpeaking = false;
    window.nekoBubbleTimer = null;
    
    function queueSpeechBubble(message, duration = 4000, priority = false) {
      if (priority) {
        window.nekoPendingMessages = [];
        if (window.nekoBubbleTimer) {
          clearTimeout(window.nekoBubbleTimer);
          window.nekoBubbleTimer = null;
        }
        window.nekoPendingMessages.push({ message, duration });
        if (!window.nekoIsSpeaking) {
          processNextMessage();
        } else {
          nekoBubble.style.display = "none";
          window.nekoIsSpeaking = false;
          processNextMessage();
        }
      } else {
        window.nekoPendingMessages.push({ message, duration });
        if (!window.nekoIsSpeaking) {
          processNextMessage();
        }
      }
    }
    
    function processNextMessage() {
      if (window.nekoPendingMessages.length === 0) {
        window.nekoIsSpeaking = false;
        return;
      }
      
      window.nekoIsSpeaking = true;
      const { message, duration } = window.nekoPendingMessages.shift();
      
      showSpeechBubble(message);
      
      window.nekoBubbleTimer = setTimeout(() => {
        hideSpeechBubble();
        setTimeout(() => {
          processNextMessage();
        }, 500);
      }, duration);
    }
  
    function showSpeechBubble(message) {
      nekoBubble.style.display = "block";
      nekoBubble.innerHTML = "";
      
      const characters = [];
      const words = message.split(" ");
      
      words.forEach((word, wordIndex) => {
        const needsSpace = wordIndex < words.length - 1;
        
        word.split("").forEach(char => {
          const span = document.createElement("span");
          span.textContent = char;
          nekoBubble.appendChild(span);
          characters.push({
            span: span,
            delay: 50
          });
        });
        
        if (needsSpace) {
          const span = document.createElement("span");
          span.textContent = " ";
          nekoBubble.appendChild(span);
          characters.push({
            span: span,
            delay: 50
          });
        }
      });
      
      function revealNextCharacter(list) {
        if (list.length === 0) return;
        
        const next = list.shift();
        next.span.classList.add("revealed");
        
        setTimeout(() => {
          revealNextCharacter(list);
        }, next.delay);
      }
      
      revealNextCharacter([...characters]);
      updateBubblePosition();
    }
    
    function hideSpeechBubble() {
      nekoBubble.style.display = "none";
    }
    
    function updateBubblePosition() {
      if (nekoBubble.style.display === "none") return;
      
      const catX = parseInt(nekoEl.style.left) || 0;
      const catY = parseInt(nekoEl.style.top) || 0;
      
      nekoBubble.style.left = (catX + 5) + "px";
      nekoBubble.style.top = (catY - nekoBubble.offsetHeight - 10) + "px";
    }
    
    function updateControlPanelPosition() {
      return;
    }
    
    function getWeather() {
      const weatherResult = document.getElementById('oneko-weather-result');
      weatherResult.innerHTML = "Checking the weather...";
      weatherResult.classList.add('active');
      
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            
            fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=metric&appid=886705b4c1182eb1c69f28eb8c520e20`)
              .then(response => response.json())
              .then(data => {
                const temp = Math.round(data.main.temp);
                const condition = data.weather[0].main;
                const location = data.name;
                
                weatherResult.innerHTML = `
                  <div><strong>${location}</strong></div>
                  <div>${condition}, ${temp}°C / ${Math.round(temp * 9/5 + 32)}°F</div>
                `;
                
                queueSpeechBubble(`${condition}, ${temp}°C in ${location}`, 9000, true);
              })
              .catch(error => {
                weatherResult.innerHTML = "Couldn't fetch weather data. Try again later.";
                queueSpeechBubble("Sorry, I couldn't get the weather information!", 3000, true);
              });
          },
          (error) => {
            weatherResult.innerHTML = "Location access denied. Please enable location services.";
            queueSpeechBubble("I need your location to check the weather!", 3000, true);
          }
        );
      } else {
        weatherResult.innerHTML = "Geolocation is not supported by your browser.";
        queueSpeechBubble("Your browser doesn't support location services!", 3000, true);
      }
    }
    
    function showTime() {
      const timeResult = document.getElementById('oneko-time-result');
      const now = new Date();
      
      const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      };
      
      const timeString = now.toLocaleDateString(undefined, options);
      timeResult.innerHTML = timeString;
      timeResult.classList.add('active');
      
      queueSpeechBubble(`${now.toLocaleTimeString()}`, 9000, true);
    }
    
    function showMotivation() {
      const motivationResult = document.getElementById('oneko-motivation-result');
      const randomMessage = motivationalMessages[Math.floor(Math.random() * motivationalMessages.length)];
      
      motivationResult.innerHTML = randomMessage;
      motivationResult.classList.add('active');
      
      queueSpeechBubble(randomMessage, 9000, true);
    }
  
    let nekoPosX = 32;
    let nekoPosY = 32;
    let mousePosX = 0;
    let mousePosY = 0;
    let frameCount = 0;
    let idleTime = 0;
    let idleAnimation = null;
    let idleAnimationFrame = 0;
    let lastMessageTime = 0;
    let messageIndex = 0;
    let isTreating = false;
    let treatAnimationFrame = 0;
    let lastClickTime = 0;
    const nekoSpeed = 10;
    
    const spriteSets = {
      idle: [[-3, -3]],
      alert: [[-7, -3]],
      scratch: [
        [-5, 0],
        [-6, 0],
        [-7, 0],
      ],
      tired: [[-3, -2]],
      treat: [
        [0, -4],
        [-1, -4],
      ],
      sleeping: [
        [-2, 0],
        [-2, -1],
      ],
      N: [
        [-1, -2],
        [-1, -3],
      ],
      NE: [
        [0, -2],
        [0, -3],
      ],
      E: [
        [-3, 0],
        [-3, -1],
      ],
      SE: [
        [-5, -1],
        [-5, -2],
      ],
      S: [
        [-6, -3],
        [-7, -2],
      ],
      SW: [
        [-5, -3],
        [-6, -1],
      ],
      W: [
        [-4, -2],
        [-4, -3],
      ],
      NW: [
        [-1, 0],
        [-1, -1],
      ],
    };
  
    document.onmousemove = (event) => {
      mousePosX = event.clientX;
      mousePosY = event.clientY;
    };
    
    document.getElementById('oneko-weather-button').addEventListener('click', getWeather);
    document.getElementById('oneko-time-button').addEventListener('click', showTime);
    document.getElementById('oneko-motivation-button').addEventListener('click', showMotivation);
    
    document.querySelector('.oneko-close-button').addEventListener('click', () => {
      controlPanel.style.display = 'none';
    });
    
    nekoEl.addEventListener('click', (event) => {
      const currentTime = new Date().getTime();
      
      if (currentTime - lastClickTime < 300) {
        updateControlPanelPosition();
        controlPanel.style.display = controlPanel.style.display === 'block' ? 'none' : 'block';
        
        document.querySelectorAll('.oneko-result').forEach(el => {
          el.classList.remove('active');
        });
        
        event.preventDefault();
      } else {
        meowAudio.currentTime = 0;
        meowAudio.play().catch(e => console.log("Audio playback failed:", e));
        
        isTreating = true;
        treatAnimationFrame = 0;
        
        const randomMessage = clickMessages[Math.floor(Math.random() * clickMessages.length)];
        queueSpeechBubble(randomMessage, 3000, true);
      }
      
      lastClickTime = currentTime;
    });
  
    function maybeShowMessage() {
      const now = Date.now();
      if (now - lastMessageTime > messages[messageIndex].delay) {
        queueSpeechBubble(messages[messageIndex].text, 4000);
        lastMessageTime = now;
        messageIndex = (messageIndex + 1) % messages.length;
      }
    }
  
    function setSprite(name, frame) {
      const sprite = spriteSets[name][frame % spriteSets[name].length];
      nekoEl.style.backgroundPosition = `${sprite[0] * 32}px ${sprite[1] * 32}px`;
    }
  
    function resetIdleAnimation() {
      idleAnimation = null;
      idleAnimationFrame = 0;
    }
  
    function idle() {
      if (isTreating) {
        setSprite("treat", Math.floor(treatAnimationFrame / 3));
        treatAnimationFrame++;
        
        if (treatAnimationFrame >= 12) {
          isTreating = false;
        }
        return;
      }
      
      idleTime += 1;
  
      if (
        idleTime > 10 &&
        Math.floor(Math.random() * 70) == 0 &&
        idleAnimation == null
      ) {
        idleAnimation = ["sleeping", "scratch"][Math.floor(Math.random() * 2)];
      }
  
      switch (idleAnimation) {
        case "sleeping":
          if (idleAnimationFrame < 8) {
            setSprite("tired", 0);
            break;
          }
          setSprite("sleeping", Math.floor(idleAnimationFrame / 4));
          if (idleAnimationFrame > 192) {
            resetIdleAnimation();
          }
          break;
        case "scratch":
          setSprite("scratch", idleAnimationFrame);
          if (idleAnimationFrame > 9) {
            resetIdleAnimation();
          }
          break;
        default:
          setSprite("idle", 0);
          return;
      }
      idleAnimationFrame += 1;
    }
  
    function frame() {
      frameCount += 1;
      const diffX = nekoPosX - mousePosX;
      const diffY = nekoPosY - mousePosY;
      const distance = Math.sqrt(diffX ** 2 + diffY ** 2);
  
      updateBubblePosition();
      updateControlPanelPosition();
      maybeShowMessage();
      
      if (isTreating) {
        idle();
        return;
      }
  
      if (distance < nekoSpeed || distance < 48) {
        idle();
        return;
      }
  
      idleAnimation = null;
      idleAnimationFrame = 0;
  
      if (idleTime > 1) {
        setSprite("alert", 0);
        idleTime = Math.min(idleTime, 7);
        idleTime -= 1;
        return;
      }
  
      let direction = diffY / distance > 0.5 ? "N" : "";
      direction += diffY / distance < -0.5 ? "S" : "";
      direction += diffX / distance > 0.5 ? "W" : "";
      direction += diffX / distance < -0.5 ? "E" : "";
      setSprite(direction, frameCount);
  
      nekoPosX -= (diffX / distance) * nekoSpeed;
      nekoPosY -= (diffY / distance) * nekoSpeed;
  
      nekoEl.style.left = `${nekoPosX - 16}px`;
      nekoEl.style.top = `${nekoPosY - 16}px`;
    }
  
    window.onekoInterval = setInterval(frame, 100);
    setTimeout(() => {
      queueSpeechBubble("Double-click for more features!", 5000);
    }, 1000);
})();
