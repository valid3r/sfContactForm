/** Method that sends an XHR Post request with the user submitted data */
function sendEmail(data) {
  // Show loading
  document.getElementById('backdrop').style.display = 'block'
  document.getElementById('modalLoading').style.display = 'block'
  document.getElementById('modalLoading').classList.add('show')

  data = JSON.stringify(data)

  var request = new XMLHttpRequest()

  request.open('POST', '/sendEmail/' + data, true)

  request.onreadystatechange = function () {
    if (this.status >= 200 && this.status < 400) {
      document.getElementById('backdrop').style.display = 'none'
      document.getElementById('modalLoading').style.display = 'none'
      document.getElementById('modalLoading').classList.remove('show')

      response = JSON.parse(this.response)
      console.log('Response: ' + response.code)

      if (response.email_sent == true) {
        $('#successModal').modal('show')
      } else {
        $('#failModal').modal('show')
        console.log(response.message)
      }
    } else {
      $('#failModal').modal('show')
      console.log(this.responseText)
    }
  }

  request.onerror = function () {
    $('#failModal').modal('show')
    console.log(this.responseText)
  }

  request.send(data)
}

/** Method that checks if required inputs are filled */
function submitData(form, e) {
  e.preventDefault()

  smtpAdress = document.getElementById('smtpAdress').value
  smtpPort = document.getElementById('smtpPort').value
  smtpUsername = document.getElementById('smtpUsername').value
  smtpPassword = document.getElementById('smtpPassword').value
  vorname = document.getElementById('name').value
  nachname = document.getElementById('nachname').value
  subject = document.getElementById('subject').value
  // from
  email = document.getElementById('email').value
  to_email = document.getElementById('to_email').value
  message = document.getElementById('message').value

  var data = {
    smtpAdress: smtpAdress,
    smtpPort: smtpPort,
    smtpUsername: smtpUsername,
    smtpPassword: smtpPassword,
    vorname: vorname,
    nachname: nachname,
    subject: subject,
    email: email,
    toEmail: to_email,
    message: message,
  }

  sendEmail(data)
}

function deleteContact(id) {
  var request = new XMLHttpRequest()

  request.open('POST', '/deleteContact/' + id, true)

  request.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      getAllContacts()
    }
  }

  request.send()

  // window.location.href = '/contacts'
}

function deleteOlderThan2Weeks() {
  console.log('Delete Older than 2 weeks')

  var request = new XMLHttpRequest()

  request.open('POST', '/massDeleteContacts', true)

  request.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      getAllContacts()
      //response = JSON.parse(this.response)
    }
  }

  request.send()
}

function getContactInfo(id) {
  console.log('Contact Info with id: ' + id)

  var request = new XMLHttpRequest()

  request.open('POST', '/getContactInfo/' + id, true)

  request.setRequestHeader(
    'Content-Type',
    'application/x-www-form-urlencoded; charset=UTF-8',
  )

  const infoModal = new bootstrap.Modal(
    document.getElementById('infoModal'),
    {},
  )

  request.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log('Response text: ' + this.responseText)

      // Append response to modal
      document.getElementById('info-content').innerHTML = this.responseText

      // Show modal
      infoModal.show()
    }
  }

  request.send()
}

function getAllContacts() {
  var request = new XMLHttpRequest()

  request.open('POST', '/getAllContacts', true)

  request.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      response = JSON.parse(this.response)

      document.getElementById('table-data').innerHTML = response.response

      // Append response to modal
      //document.getElementById('info-content').innerHTML = this.responseText

      // Show modal
      //infoModal.show()
    }
  }

  request.send()
}
