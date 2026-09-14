document.addEventListener('DOMContentLoaded', () => {
    const introWrapper = document.getElementById('intro-video-wrapper');
    const introVideo = document.getElementById('intro-video');
    const btnSkip = document.getElementById('btn-skip-intro');

    if (!introWrapper) return;

    // Check if Intro was already shown in this session
    const introShown = sessionStorage.getItem('introShown');
    
    if (introShown === 'true') {
        // Skip intro immediately without animation if already shown
        introWrapper.style.display = 'none';
        return;
    }

    // Function to hide Intro
    const skipIntro = () => {
        introWrapper.classList.add('hide');
        if (introVideo) {
            introVideo.pause();
        }
        sessionStorage.setItem('introShown', 'true');
        
        // Optionally remove from DOM after fade out
        setTimeout(() => {
            introWrapper.style.display = 'none';
        }, 700);
    };

    // Event listeners
    if (btnSkip) {
        btnSkip.addEventListener('click', skipIntro);
    }

    if (introVideo) {
        introVideo.addEventListener('ended', skipIntro);
        
        // Handle mobile autoplay restrictions
        const playPromise = introVideo.play();
        if (playPromise !== undefined) {
            playPromise.catch(error => {
                // Auto-play was prevented
                // You could change the skip button text to "XEM VIDEO" here 
                // and wait for user interaction to play it, 
                // but usually muted videos play fine.
                console.log('Autoplay prevented by browser', error);
                
                // If it really fails, we just show the skip button clearly
                if(btnSkip) {
                    btnSkip.innerHTML = 'BỎ QUA <i class="fa-solid fa-arrow-right arrow-icon"></i>';
                }
            });
        }
    }

    // Handle ESC key to skip
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !introWrapper.classList.contains('hide')) {
            skipIntro();
        }
    });
});
