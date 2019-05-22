const $body = document.querySelector('body')
const $header = document.querySelector('header')
const $logo = $header.querySelector('h1')
const $social = $header.querySelector('.social')
const $nav = $header.querySelector('nav')
const $pres = document.querySelector('.presentation')
const $burger = $header.querySelector('.burgermenu')
const $lines = $burger.querySelector('.lines')
const $cover = document.querySelectorAll('.cover')

const see = () => {
    $header.style.backgroundColor = '#ffffff'
    $header.style.borderBottom = '5px solid #f2f2f2'
    $logo.style.display = 'block'
    $logo.style.opacity = '1'
}

if (window.location.href.indexOf("contact") != -1) {
    see()
}
else {
    window.addEventListener('scroll', () => {
        let limit = 550
        let position = $pres.offsetHeight - window.pageYOffset
        if (position < limit) {
            see()
        }
        else if (position > limit) {
            $header.style.backgroundColor = 'rgba(255, 255, 255, 0)'
            $header.style.borderBottom = 'none'
            $logo.style.opacity = '0'
            $logo.style.display = 'none'

        }
    })
}

$burger.addEventListener('click', () => {
    $body.classList.toggle('is-active')
    $lines.classList.toggle('is-active')
    $header.classList.toggle('is-active')
    $logo.classList.toggle('is-active')
    $social.classList.toggle('is-active')
    $nav.classList.toggle('is-active')
})

window.addEventListener('resize', () => {
    if (window.innerWidth < 690) {
        for (let i = 0; i < $cover.length; i++) {
            $cover[i].style.height = window.innerWidth+'px'
        }
    }
    else {
        for (let i = 0; i < $cover.length; i++) {
            $cover[i].style.height = '450px'
        }
    }
})


// window.addEventListener("load", function(event) {
//     console.log("Toutes les ressources sont chargées !");
// });
