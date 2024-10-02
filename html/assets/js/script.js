let currentAccountIndex = 0; // Index to keep track of the current account

function updateModal(account) {
  const accountName = document.getElementById("modalAccountName");
  const accountAddress = document.getElementById("modalAccountAddress");
  const accountEmail = document.getElementById("modalAccountEmail");
  const accountPhone = document.getElementById("modalAccountPhone");
  const accountWebsite = document.getElementById("modalAccountWebsite");
  const contactsDiv = document.getElementById("modalContacts");

  // Update the modal with account details
  accountName.innerText = account.account_name;
  accountAddress.innerText = account.address || "N/A";
  accountEmail.innerText = account.account_email || "N/A";
  accountPhone.innerText = account.account_phone || "N/A";
  accountWebsite.innerText = account.website || "N/A";

  // Clear previous contacts
  contactsDiv.innerHTML = "";

  if (account.contacts.length > 0) {
    // Loop through all contacts and create a collapsible section for each one
    account.contacts.forEach((contact, index) => {
      const contactId = `collapseContact${index}`;
      const contactInfo = document.createElement("div");
      contactInfo.classList.add("contact-info", "mb-3", "border", "p-3");

      contactInfo.innerHTML = `
                <h5>
                    <a class="btn btn-link" data-toggle="collapse" href="#${contactId}" role="button" aria-expanded="false" aria-controls="${contactId}">
                        ${contact.first_name} ${contact.last_name} - ${
        contact.job_title || "N/A"
      } (${contact.contact_status || "N/A"})
                    </a>
                </h5>
                <div class="collapse" id="${contactId}">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Email:</strong> ${
                              contact.contact_email || "N/A"
                            }</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Phone:</strong> ${
                              contact.contact_phone || "N/A"
                            }</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Mobile:</strong> ${
                              contact.mobile_phone || "N/A"
                            }</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Notes:</strong> ${
                              contact.notes || "N/A"
                            }</p>
                        </div>
                    </div>
                </div>
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
