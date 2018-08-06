const axios = require('axios')
const instance = axios.create({
    baseURL: 'http://didirkfleischer.com/livedemo/companywatchlist',
    headers: {
        'X-CSRF-TOKEN': Laravel.csrfToken,
    }
})

export default instance;