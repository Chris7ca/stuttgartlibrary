/**
 * 
 * @param {String} el 
 * @param {String} placeholder 
 * @param {String} route 
 * @param {String} keyText 
 * @param {String} keyValue 
 */
const ajaxSelect = (el, placeholder, route, keyText, keyValue) => {
    return new SlimSelect({
        select: el,
        placeholder: placeholder,
        searchFilter: (option, search) => {
            let words = search.toUpperCase().split(' ')
            return words.filter(word => option.text.toUpperCase().includes(word)).length > 0
        },
        ajax: function (search, callback) {
            
            if (search.length < 3) {
                callback('Need 3 characters')
                return
            }

            route.urlParams.find = search 
            axios.get(route)
            .then( response => {

                let dataSelect = []

                response.data.forEach(data => {
                    dataSelect.push({
                        text: data[keyText],
                        value: data[keyValue]
                    })
                })

                callback(dataSelect)
            })
            .catch( error => {
                UIkit.notification(error, 'danger')
                callback(false)
            })
        }
    })
}

/**
 * 
 * @param {String} el 
 * @param {String} placeholder 
 */
const simpleSelect = (el, placeholder) => {
    return new SlimSelect({
        select: el,
        placeholder: placeholder
    })
}

module.exports.ajaxSelect = ajaxSelect

module.exports.simpleSelect = simpleSelect