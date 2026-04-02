import { useState, useEffect } from "react"
import axios from "axios"
import "./App.css"

function App() {

  const [form, setForm] = useState({
    book_name: "",
    isbn: "",
    book_title: "",
    author_name: "",
    publisher_name: ""
  })

  const [books, setBooks] = useState([])
  const [message, setMessage] = useState("")

  const fetchBooks = async () => {
    try {
      const res = await axios.get("http://localhost:5000/api/books")
      setBooks(res.data.data)  
    } catch (err) {
      console.log(err)
    }
  }

  useEffect(() => {
    fetchBooks()
  }, [])

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value })
  }

  const validate = () => {
    if (!form.book_name || !form.isbn || !form.book_title || !form.author_name || !form.publisher_name)
      return "All fields required"

    if (!/^[0-9\-]+$/.test(form.isbn))
      return "Invalid ISBN"

    return null
  }

  const handleSubmit = async (e) => {
    e.preventDefault()

    const error = validate()
    if(error){
      setMessage(error)
      return
    }

    try {
      await axios.post("http://localhost:5000/api/books", form)
      setMessage("Book added")

      fetchBooks()

      setForm({
        book_name:"",
        isbn:"",
        book_title:"",
        author_name:"",
        publisher_name:""
      })

    } catch(err){
      setMessage(err.response?.data?.message || "Error")
    }
  }

  const deleteBook = async (isbn) => {
    await axios.delete(`http://localhost:5000/api/books/${isbn}`)
    fetchBooks()
  }

  const updateBook = async (isbn) => {
    const newTitle = prompt("Enter new title")
    if(!newTitle) return

    await axios.put(`http://localhost:5000/api/books/${isbn}`, {
      book_title: newTitle
    })

    fetchBooks()
  }

  return (
    <div className="container">

      <h2> Library Management System</h2>

      <form onSubmit={handleSubmit}>
        <input name="book_name" value={form.book_name} onChange={handleChange} placeholder="Book Name" />
        <input name="isbn" value={form.isbn} onChange={handleChange} placeholder="ISBN" />
        <input name="book_title" value={form.book_title} onChange={handleChange} placeholder="Title" />
        <input name="author_name" value={form.author_name} onChange={handleChange} placeholder="Author" />
        <input name="publisher_name" value={form.publisher_name} onChange={handleChange} placeholder="Publisher" />

        <br />
        <button type="submit">Add Book</button>
      </form>

      <p>{message}</p>

      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>ISBN</th>
            <th>Title</th>
            <th>Author</th>
            <th>Publisher</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
          {Array.isArray(books) && books.map((b,i)=>(
            <tr key={i}>
              <td>{b.book_name}</td>
              <td>{b.isbn}</td>
              <td>{b.book_title}</td>
              <td>{b.author_name}</td>
              <td>{b.publisher_name}</td>
              <td>
                <button onClick={()=>updateBook(b.isbn)}>Update</button>
                <button onClick={()=>deleteBook(b.isbn)}>Delete</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

    </div>
  )
}

export default App
