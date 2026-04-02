const mongoose = require('mongoose')

const bookSchema = new mongoose.Schema({
    book_name: { type: String, required: true },
    isbn: { type: String, required: true, unique: true },
    book_title: { type: String, required: true },
    author_name: { type: String, required: true },
    publisher_name: { type: String, required: true }
})

module.exports = mongoose.model("Book", bookSchema)
