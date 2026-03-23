const User = require('../models/userModel')

// CREATE USER
const createUser = async (req, res, next) => {
    try {
        const { name, email, phone, password } = req.body

        const user = await User.create({ name, email, phone, password })

        res.status(201).json(user)

    } catch (err) {

        // Duplicate error
        if (err.code === 11000) {
            const field = Object.keys(err.keyValue)[0]
            return res.status(400).json({
                error: `${field} already exists`
            })
        }

        next(err)
    }
}

module.exports = { createUser }
