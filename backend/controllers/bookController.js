const Book = require('../models/book')


// INSERT
const createBook = async (req,res) => {
    try {
        const book = await Book.create(req.body)
        res.json(book)
    } catch (err) {
        if(err.code === 11000){
            return res.status(400).json({error: "ISBN already exists"})
        }
        res.status(400).json({error: err.message})
    }
}

// GET ALL
const getBooks = async (req,res) => {
    const books = await Book.find()
    res.json(books)
}

// DELETE
const deleteBook = async (req,res) => {
    const { isbn } = req.params
    await Book.findOneAndDelete({ isbn })
    res.json({message: "Deleted"})
}

// UPDATE
const updateBook = async (req,res) => {
    const { isbn } = req.params
    const updated = await Book.findOneAndUpdate({ isbn }, req.body, { new: true })
    res.json(updated)
}

module.exports = { createBook, getBooks, deleteBook, updateBook }
