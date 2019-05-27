const br = () => {
    if (window.location.href.indexOf("My-Space") != -1) {
        const $text = document.querySelectorAll('modif')
        for (let i = 0; i < $text.length; i++) {
            console.log(i);
            
            // let text = $text.textContent
            // let legend = $legend.textContent
            // text = text.replace(/(\r\n|\n|\r)/g, '<br>')
            // text = text.replace('U+1F3A5', '🎥')
            // $text.innerHTML = text
            // legend = legend.replace('U+1F3A5', '🎥')
            // $legend.innerHTML = legend
        }
    }
}
br()