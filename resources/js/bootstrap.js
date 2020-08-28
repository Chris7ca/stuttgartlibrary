window._ = require('lodash')

window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

let token = document.head.querySelector('meta[name="csrf-token"]')

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
    console.error('CSRF token not found!')
}

const avatarElements = document.querySelectorAll('.uk-avatar-without-image')

const imageFromInitials = name => {

    if (name == null) return 

    const canvas = document.createElement('canvas')
    const context = canvas.getContext('2d')

    canvas.width = 40
    canvas.height = 40

    context.fillStyle = '#FFFFFF'
    context.fillRect(0, 0, 40, 40)

    context.fillStyle = '#579fb950'
    context.fillRect(0, 0, 40, 40)

    context.fillStyle = '#579fb9'
    context.textBaseline = 'middle'
    context.textAlign = 'center'
    context.font = '25px Arial'
    context.fillText(name, 20, 22.5)

    return canvas.toDataURL()
}

avatarElements.forEach(avatarElement => {
    avatarElement.setAttribute('src', imageFromInitials(
        avatarElement.getAttribute('data-name').split(' ')[0].split('')[0])
    )
})
