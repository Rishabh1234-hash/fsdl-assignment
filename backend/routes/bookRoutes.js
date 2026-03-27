const express = require('express')
const router = express.Router()

const {
    createBook,
    getBooks,
    deleteBook,
    updateBook
} = require('../controllers/bookController')

router.post('/', createBook)
router.get('/', getBooks)
router.delete('/:isbn', deleteBook)
router.put('/:isbn', updateBook)

module.exports = router
