require('dotenv').config()
const express = require('express')
const mongoose = require('mongoose')
const cors = require('cors')

const userRoutes = require('./routes/userRoutes')
const errorHandler = require('./middleware/errorMiddleware')

const app = express()

app.use(cors())
app.use(express.json())

// Logger middleware
app.use((req, res, next) => {
    console.log(req.method, req.path)
    next()
})

// Routes
app.use('/api/users', userRoutes)

// Home route
app.get('/', (req, res) => {
    res.json({ message: "API Running" })
})

// Error middleware (LAST)
app.use(errorHandler)

// DB Connection
mongoose.connect(process.env.MONGO_URI)
.then(() => {
    console.log("MongoDB connected")
    app.listen(process.env.PORT, () => {
        console.log("Server running on port", process.env.PORT)
    })
})
.catch(err => console.log(err))
