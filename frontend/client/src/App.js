import { useState } from "react"
import axios from "axios"
import "./App.css"

function App() {

  const [form, setForm] = useState({
    name: "",
    email: "",
    phone: "",
    password: ""
  })

  const [message, setMessage] = useState("")

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value })
  }

  // VALIDATION
  const validate = () => {

    if (!form.name.trim())
      return "Name is required"

    if (!/^[A-Za-z ]{3,}$/.test(form.name))
      return "Name must be at least 3 letters"

    if (!form.email)
      return "Email required"

    if (!/^\S+@\S+\.\S+$/.test(form.email))
      return "Invalid email"

    if (!form.phone)
      return "Phone required"

    if (!/^[6-9][0-9]{9}$/.test(form.phone))
      return "Invalid phone number"

    if (!form.password)
      return "Password required"

    if (form.password.length < 6)
      return "Min 6 characters"

    if (!/[A-Z]/.test(form.password))
      return "Must contain uppercase letter"

    if (!/[0-9]/.test(form.password))
      return "Must contain number"

    if (!/[!@#$%^&*]/.test(form.password))
      return "Must contain special character"

    return null
  }

  const handleSubmit = async (e) => {
    e.preventDefault()

    const error = validate()
    if (error) {
      setMessage(" " + error)
      return
    }

    try {
      await axios.post("http://localhost:4000/api/users", form)

      setMessage("Registered Successfully!")

      // Clear form
      setForm({
        name: "",
        email: "",
        phone: "",
        password: ""
      })

    } catch (err) {
      console.log(err)

      if (err.response && err.response.data.error) {
        setMessage(" " + err.response.data.error)
      } else {
        setMessage("Server error")
      }
    }
  }

  return (
    <div className="bg">

      <div className="card">
        <h2>User Registration</h2>

        <form onSubmit={handleSubmit}>

          <div className="inputBox">
            <input name="name" value={form.name} onChange={handleChange} required />
            <label>Name</label>
          </div>

          <div className="inputBox">
            <input name="email" value={form.email} onChange={handleChange} required />
            <label>Email</label>
          </div>

          <div className="inputBox">
            <input name="phone" value={form.phone} onChange={handleChange} required />
            <label>Phone</label>
          </div>

          <div className="inputBox">
            <input type="password" name="password" value={form.password} onChange={handleChange} required />
            <label>Password</label>
          </div>

          <button type="submit">Submit</button>
        </form>

        <p className="msg">{message}</p>
      </div>

    </div>
  )
}

export default App
