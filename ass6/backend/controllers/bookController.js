const Book = require('../models/Book')

// CREATE
const createBook = async (req,res,next) => {
    try {
        const book = await Book.create(req.body)

        res.status(201).json({
            success: true,
            data: book,
            links: {
                self: `/api/books/${book.isbn}`,
                all: `/api/books`
            }
        })

    } catch (err) {

        if(err.code === 11000){
            return res.status(409).json({
                success:false,
                message:"ISBN already exists"
            })
        }

        next(err)
    }
}

// GET ALL (with filter + pagination)
const getBooks = async (req,res,next) => {
    try {
        const { author, title, page = 1, limit = 5 } = req.query

        let filter = {}
        if(author) filter.author_name = author
        if(title) filter.book_title = title

        const books = await Book.find(filter)
            .skip((page - 1) * limit)
            .limit(parseInt(limit))

        res.json({
            success:true,
            count: books.length,
            page: Number(page),
            data: books
        })

    } catch (err) {
        next(err)
    }
}

// GET SINGLE
const getSingleBook = async (req,res,next) => {
    try {
        const book = await Book.findOne({ isbn: req.params.isbn })

        if(!book){
            return res.status(404).json({
                success:false,
                message:"Book not found"
            })
        }

        res.json({
            success:true,
            data:book
        })

    } catch (err) {
        next(err)
    }
}

// UPDATE
const updateBook = async (req,res,next) => {
    try {
        const updated = await Book.findOneAndUpdate(
            { isbn: req.params.isbn },
            req.body,
            { new: true }
        )

        if(!updated){
            return res.status(404).json({
                success:false,
                message:"Book not found"
            })
        }

        res.json({
            success:true,
            data:updated
        })

    } catch (err) {
        next(err)
    }
}

// DELETE
const deleteBook = async (req,res,next) => {
    try {
        const deleted = await Book.findOneAndDelete({ isbn: req.params.isbn })

        if(!deleted){
            return res.status(404).json({
                success:false,
                message:"Book not found"
            })
        }

        res.json({
            success:true,
            message:"Book deleted"
        })

    } catch (err) {
        next(err)
    }
}

module.exports = {
    createBook,
    getBooks,
    getSingleBook,
    updateBook,
    deleteBook
}
