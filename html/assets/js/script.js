// script.js

let currentAccountIndex = 0; // Index to keep track of the current account

function updateModal(account) {
  const accountName = document.getElementById("modalAccountName");
  const contactsDiv = document.getElementById("modalContacts");

  // Update the modal with account name
  accountName.innerText = account.account_name;

  // Clear previous contacts
  contactsDiv.innerHTML = "";

  if (account.contacts.length > 0) {
    // Loop through all contacts and display their details
    account.contacts.forEach((contact) => {
      console.log(contact);
      const contactInfo = document.createElement("div");
      contactInfo.classList.add("contact-info");

      contactInfo.innerHTML = `
                <h5>${contact.first_name} ${contact.last_name}</h5>
                <p>Email: ${contact.contact_email || "N/A"}</p>
                <p>Phone: ${contact.contact_phone || "N/A"}</p>
                <p>Mobile Phone: ${contact.mobile_phone || "N/A"}</p>
                <p>Job Title: ${contact.job_title || "N/A"}</p>
                <p>Status: ${contact.contact_status || "N/A"}</p>
                <p>Notes: ${contact.notes || "N/A"}</p>
                <hr>
            `;

      contactsDiv.appendChild(contactInfo);
    });
  } else {
    contactsDiv.innerHTML = "<p>No contacts available</p>";
  }
}

// Example for moving to next target
function nextTarget() {
  currentAccountIndex++;
  if (currentAccountIndex >= accounts.length) {
    currentAccountIndex = 0; // Start from the first account again if we've reached the end
  }
  updateModal(accounts[currentAccountIndex]);
}

// Example for moving to previous target
function previousTarget() {
  currentAccountIndex--;
  if (currentAccountIndex < 0) {
    currentAccountIndex = accounts.length - 1; // Loop back to the last account
  }
  updateModal(accounts[currentAccountIndex]);
}

// Attach event listeners for "Next" and "Previous" buttons
document.getElementById("nextTargetBtn").addEventListener("click", nextTarget);
document
  .getElementById("previousTargetBtn")
  .addEventListener("click", previousTarget);

// Initialize the modal with the first account's details
if (accounts.length > 0) {
  updateModal(accounts[0]); // Start with the first account
}
