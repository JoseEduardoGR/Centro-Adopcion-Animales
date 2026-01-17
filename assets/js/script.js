// DOM Content Loaded
document.addEventListener("DOMContentLoaded", () => {
  initializeApp()
})

// Initialize Application
function initializeApp() {
  initializeNavigation()
  initializeFormValidation()
  initializeModals()
}

// Navigation
function initializeNavigation() {
  const hamburger = document.querySelector(".hamburger")
  const navMenu = document.querySelector(".nav-menu")

  if (hamburger && navMenu) {
    hamburger.addEventListener("click", () => {
      hamburger.classList.toggle("active")
      navMenu.classList.toggle("active")
    })

    // Close menu when clicking on a link
    document.querySelectorAll(".nav-link").forEach((link) => {
      link.addEventListener("click", () => {
        hamburger.classList.remove("active")
        navMenu.classList.remove("active")
      })
    })
  }
}

// Form Validation
function initializeFormValidation() {
  // Login Form
  const loginForm = document.getElementById("loginForm")
  if (loginForm) {
    loginForm.addEventListener("submit", validateLoginForm)
  }

  // Register Form
  const registerForm = document.getElementById("registerForm")
  if (registerForm) {
    registerForm.addEventListener("submit", validateRegisterForm)
  }

  // Edit Profile Form
  const editProfileForm = document.getElementById("editProfileForm")
  if (editProfileForm) {
    editProfileForm.addEventListener("submit", validateEditProfileForm)
  }
}

// Login Form Validation
function validateLoginForm(e) {
  e.preventDefault()

  const username = document.getElementById("username").value.trim()
  const password = document.getElementById("password").value

  let isValid = true

  // Clear previous errors
  clearErrors()

  // Username validation
  if (!username) {
    showError("usernameError", "El usuario o email es obligatorio")
    isValid = false
  }

  // Password validation
  if (!password) {
    showError("passwordError", "La contraseña es obligatoria")
    isValid = false
  }

  if (isValid) {
    e.target.submit()
  }
}

// Register Form Validation
function validateRegisterForm(e) {
  e.preventDefault()

  const username = document.getElementById("username").value.trim()
  const email = document.getElementById("email").value.trim()
  const fullName = document.getElementById("full_name").value.trim()
  const phone = document.getElementById("phone").value.trim()
  const password = document.getElementById("password").value
  const confirmPassword = document.getElementById("confirm_password").value

  let isValid = true

  // Clear previous errors
  clearErrors()

  // Username validation
  if (!username) {
    showError("usernameError", "El usuario es obligatorio")
    isValid = false
  } else if (username.length < 3) {
    showError("usernameError", "El usuario debe tener al menos 3 caracteres")
    isValid = false
  } else if (!/^[a-zA-Z0-9_]+$/.test(username)) {
    showError("usernameError", "El usuario solo puede contener letras, números y guiones bajos")
    isValid = false
  }

  // Email validation
  if (!email) {
    showError("emailError", "El email es obligatorio")
    isValid = false
  } else if (!isValidEmail(email)) {
    showError("emailError", "Ingrese un email válido")
    isValid = false
  }

  // Full name validation
  if (!fullName) {
    showError("fullNameError", "El nombre completo es obligatorio")
    isValid = false
  } else if (fullName.length < 2) {
    showError("fullNameError", "El nombre debe tener al menos 2 caracteres")
    isValid = false
  }

  // Phone validation (optional but if provided, must be valid)
  if (phone && !isValidPhone(phone)) {
    showError("phoneError", "Ingrese un número de teléfono válido")
    isValid = false
  }

  // Password validation
  if (!password) {
    showError("passwordError", "La contraseña es obligatoria")
    isValid = false
  } else if (password.length < 6) {
    showError("passwordError", "La contraseña debe tener al menos 6 caracteres")
    isValid = false
  } else if (!isStrongPassword(password)) {
    showError("passwordError", "La contraseña debe contener al menos una letra y un número")
    isValid = false
  }

  // Confirm password validation
  if (!confirmPassword) {
    showError("confirmPasswordError", "Confirme su contraseña")
    isValid = false
  } else if (password !== confirmPassword) {
    showError("confirmPasswordError", "Las contraseñas no coinciden")
    isValid = false
  }

  if (isValid) {
    e.target.submit()
  }
}

// Edit Profile Form Validation
function validateEditProfileForm(e) {
  e.preventDefault()

  const fullName = document.getElementById("edit_full_name").value.trim()
  const email = document.getElementById("edit_email").value.trim()
  const phone = document.getElementById("edit_phone").value.trim()

  let isValid = true

  // Clear previous errors
  clearErrors()

  // Full name validation
  if (!fullName) {
    showError("editFullNameError", "El nombre completo es obligatorio")
    isValid = false
  } else if (fullName.length < 2) {
    showError("editFullNameError", "El nombre debe tener al menos 2 caracteres")
    isValid = false
  }

  // Email validation
  if (!email) {
    showError("editEmailError", "El email es obligatorio")
    isValid = false
  } else if (!isValidEmail(email)) {
    showError("editEmailError", "Ingrese un email válido")
    isValid = false
  }

  // Phone validation (optional)
  if (phone && !isValidPhone(phone)) {
    showError("editPhoneError", "Ingrese un número de teléfono válido")
    isValid = false
  }

  if (isValid) {
    e.target.submit()
  }
}

// Validation Helper Functions
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(email)
}

function isValidPhone(phone) {
  const phoneRegex = /^[+]?[0-9\s\-$$$$]{10,}$/
  return phoneRegex.test(phone)
}

function isStrongPassword(password) {
  const hasLetter = /[a-zA-Z]/.test(password)
  const hasNumber = /[0-9]/.test(password)
  return hasLetter && hasNumber
}

function showError(elementId, message) {
  const errorElement = document.getElementById(elementId)
  if (errorElement) {
    errorElement.textContent = message
    errorElement.style.display = "block"
  }
}

function clearErrors() {
  const errorElements = document.querySelectorAll(".error-message")
  errorElements.forEach((element) => {
    element.textContent = ""
    element.style.display = "none"
  })
}

// Modal Functions
function initializeModals() {
  // Close modal when clicking outside
  window.addEventListener("click", (event) => {
    const modals = document.querySelectorAll(".modal")
    modals.forEach((modal) => {
      if (event.target === modal) {
        modal.style.display = "none"
      }
    })
  })
}

function openEditProfile() {
  const modal = document.getElementById("editProfileModal")
  if (modal) {
    modal.style.display = "block"
  }
}

function closeEditProfile() {
  const modal = document.getElementById("editProfileModal")
  if (modal) {
    modal.style.display = "none"
  }
}

// Adoption Functions
function adoptAnimal(animalId) {
  if (!animalId) {
    showAlert("Error: ID de animal inválido", "error")
    return
  }

  if (confirm("¿Estás seguro de que quieres solicitar la adopción de este animal?")) {
    const formData = new FormData()
    formData.append("animal_id", animalId)

    fetch("adopt_animal.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showAlert(data.message, "success")
          // Refresh page after 2 seconds
          setTimeout(() => {
            location.reload()
          }, 2000)
        } else {
          showAlert(data.message, "error")
        }
      })
      .catch((error) => {
        console.error("Error:", error)
        showAlert("Error al procesar la solicitud", "error")
      })
  }
}

function cancelAdoption(adoptionId) {
  if (!adoptionId) {
    showAlert("Error: ID de adopción inválido", "error")
    return
  }

  if (confirm("¿Estás seguro de que quieres cancelar esta solicitud de adopción?")) {
    const formData = new FormData()
    formData.append("adoption_id", adoptionId)

    fetch("cancel_adoption.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showAlert(data.message, "success")
          // Refresh page after 2 seconds
          setTimeout(() => {
            location.reload()
          }, 2000)
        } else {
          showAlert(data.message, "error")
        }
      })
      .catch((error) => {
        console.error("Error:", error)
        showAlert("Error al procesar la solicitud", "error")
      })
  }
}

// Alert System
function showAlert(message, type = "info") {
  // Remove existing alerts
  const existingAlerts = document.querySelectorAll(".dynamic-alert")
  existingAlerts.forEach((alert) => alert.remove())

  // Create new alert
  const alert = document.createElement("div")
  alert.className = `alert alert-${type} dynamic-alert`
  alert.textContent = message
  alert.style.position = "fixed"
  alert.style.top = "20px"
  alert.style.right = "20px"
  alert.style.zIndex = "9999"
  alert.style.maxWidth = "400px"
  alert.style.animation = "slideInRight 0.3s ease"

  document.body.appendChild(alert)

  // Auto remove after 5 seconds
  setTimeout(() => {
    alert.style.animation = "slideOutRight 0.3s ease"
    setTimeout(() => {
      if (alert.parentNode) {
        alert.parentNode.removeChild(alert)
      }
    }, 300)
  }, 5000)
}

// Add CSS animations for alerts
const style = document.createElement("style")
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`
document.head.appendChild(style)

// Security: Prevent XSS in dynamic content
function sanitizeHTML(str) {
  const temp = document.createElement("div")
  temp.textContent = str
  return temp.innerHTML
}

// Form input sanitization
function sanitizeInput(input) {
  if (typeof input !== "string") return input
  return input.trim().replace(/[<>]/g, "")
}

// Debounce function for search/filter inputs
function debounce(func, wait) {
  let timeout
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout)
      func(...args)
    }
    clearTimeout(timeout)
    timeout = setTimeout(later, wait)
  }
}

// Local Storage helpers
function saveToLocalStorage(key, data) {
  try {
    localStorage.setItem(key, JSON.stringify(data))
  } catch (error) {
    console.warn("Could not save to localStorage:", error)
  }
}

function getFromLocalStorage(key) {
  try {
    const data = localStorage.getItem(key)
    return data ? JSON.parse(data) : null
  } catch (error) {
    console.warn("Could not read from localStorage:", error)
    return null
  }
}

// Image loading with error handling
function loadImage(src, fallbackSrc = "/placeholder.svg?height=300&width=300") {
  return new Promise((resolve) => {
    const img = new Image()
    img.onload = () => resolve(src)
    img.onerror = () => resolve(fallbackSrc)
    img.src = src
  })
}

// Initialize image lazy loading
function initializeLazyLoading() {
  const images = document.querySelectorAll("img[data-src]")

  if ("IntersectionObserver" in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const img = entry.target
          img.src = img.dataset.src
          img.classList.remove("lazy")
          imageObserver.unobserve(img)
        }
      })
    })

    images.forEach((img) => imageObserver.observe(img))
  } else {
    // Fallback for older browsers
    images.forEach((img) => {
      img.src = img.dataset.src
      img.classList.remove("lazy")
    })
  }
}

// Initialize on page load
document.addEventListener("DOMContentLoaded", () => {
  initializeLazyLoading()
})

// Service Worker registration for offline support
if ("serviceWorker" in navigator) {
  window.addEventListener("load", () => {
    navigator.serviceWorker
      .register("/sw.js")
      .then((registration) => {
        console.log("ServiceWorker registration successful")
      })
      .catch((error) => {
        console.log("ServiceWorker registration failed")
      })
  })
}

// Accessibility improvements
function initializeAccessibility() {
  // Add skip link
  const skipLink = document.createElement("a")
  skipLink.href = "#main-content"
  skipLink.textContent = "Saltar al contenido principal"
  skipLink.className = "skip-link sr-only"
  skipLink.style.cssText = `
        position: absolute;
        top: -40px;
        left: 6px;
        background: #000;
        color: #fff;
        padding: 8px;
        text-decoration: none;
        z-index: 10000;
    `
  skipLink.addEventListener("focus", function () {
    this.style.top = "6px"
  })
  skipLink.addEventListener("blur", function () {
    this.style.top = "-40px"
  })

  document.body.insertBefore(skipLink, document.body.firstChild)

  // Add main content ID if not exists
  const mainContent = document.querySelector(".main-content")
  if (mainContent && !mainContent.id) {
    mainContent.id = "main-content"
  }
}

// Initialize accessibility features
document.addEventListener("DOMContentLoaded", initializeAccessibility)

// Error handling for fetch requests
function handleFetchError(error) {
  console.error("Fetch error:", error)
  showAlert("Error de conexión. Por favor, intente nuevamente.", "error")
}

// Global error handler
window.addEventListener("error", (event) => {
  console.error("Global error:", event.error)
  // Don't show alert for every error to avoid spam
})

// Unhandled promise rejection handler
window.addEventListener("unhandledrejection", (event) => {
  console.error("Unhandled promise rejection:", event.reason)
  event.preventDefault()
})
