document.addEventListener('DOMContentLoaded', () => {
    // This is the bare minimum JavaScript. You can opt to pass no arguments to setup.
    const player = Plyr.setup('video')
    // const players = Array.from(document.querySelectorAll('.js-player')).map(p => new Plyr(p));
    
    // Expose
    window.player = player;
  
    // Bind event listener
    function on(selector, type, callback) {
      document.querySelector(selector).addEventListener(type, callback, false);
    }
  
    // Play
    on('.js-play', 'click', () => { 
      player.play();
    });
  
    // Pause
    on('.js-pause', 'click', () => { 
      player.pause();
    });
  
    // Stop
    on('.js-stop', 'click', () => { 
      player.stop();
    });
  
    // Rewind
    on('.js-rewind', 'click', () => { 
      player.rewind();
    });
  
    // Forward
    on('.js-forward', 'click', () => { 
      player.forward();
    });
});
