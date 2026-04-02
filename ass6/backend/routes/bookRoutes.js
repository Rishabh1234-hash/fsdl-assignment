const express = require('express')
const router = express.Router()

const {
    createBook,
    getBooks,
    getSingleBook,
    updateBook,
    deleteBook
} = require('../controllers/bookController')

// REST-compliant routes
router.post('/', createBook)
router.get('/', getBooks)
router.get('/:isbn', getSingleBook)
router.put('/:isbn', updateBook)
router.delete('/:isbn', deleteBook)

module.exports = router
