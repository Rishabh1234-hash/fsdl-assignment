const mongoose = require('mongoose')

const userSchema = new mongoose.Schema({
    name: {
        type: String,
        required: true,
        lowercase: true,
        trim: true
    },
    email: {
        type: String,
        required: true,
        unique: true,
        lowercase: true,
        trim: true,
        match: [/^\S+@\S+\.\S+$/, "Invalid email"]
    },
    phone: {
        type: String,
        required: true,
        unique: true,
        match: [/^[6-9][0-9]{9}$/, "Invalid phone"]
    },
    password: {
        type: String,
        required: true,
        minlength: 6
    }
})

const User = mongoose.model("User", userSchema)

// Ensure indexes
User.init()

module.exports = User
