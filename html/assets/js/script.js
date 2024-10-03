let currentAccountIndex = 0; // Index to keep track of the current account

function updateModal(account) {
  const accountName = document.getElementById("modalAccountName");
  const accountAddress = document.getElementById("modalAccountAddress");
  const accountEmail = document.getElementById("modalAccountEmail");
  const accountPhone = document.getElementById("modalAccountPhone");
  const accountWebsite = document.getElementById("modalAccountWebsite");
  const contactsDiv = document.getElementById("modalContacts");

  // Update the modal with account details, showing account ID
  accountName.innerText = `${account.account_name} (ID: ${account.account_id})`;
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
      const formId = `collapseForm${index}`;
      const contactInfo = document.createElement("div");
      contactInfo.classList.add("contact-info", "mb-3", "border", "p-3");

      // Create the collapsible structure for each contact and call logging form inside the details
      contactInfo.innerHTML = `
      <div class="row">
        <div class="col-md-12">
          <h5 style="display: inline-block;">
            <a class="btn btn-link" data-toggle="collapse" href="#${contactId}" role="button" aria-expanded="false" aria-controls="${contactId}">
                ${contact.first_name} ${contact.last_name} - ${
        contact.job_title || "N/A"
      } (ID: ${contact.contact_id}) (${contact.contact_status || "N/A"})
            </a>
          </h5>
          <span style="float: right;">
            <strong>Email:</strong> ${contact.contact_email || "N/A"} |
            <strong>Phone:</strong> <a href="tel:${
              contact.contact_phone || ""
            }"><i class="fas fa-phone"></i> ${
        contact.contact_phone || "N/A"
      }</a> |
            <strong>Mobile:</strong> <a href="tel:${
              contact.mobile_phone || ""
            }"><i class="fas fa-phone"></i> ${contact.mobile_phone || "N/A"}</a>
          </span>
        </div>
      </div>
      <div class="collapse" id="${contactId}">
          <div class="row">
              <div class="col-md-12">
                  <p><strong>Notes:</strong> ${contact.notes || "N/A"}</p>
              </div>
          </div>
          <div class="text-center">
              <button class="btn btn-link" data-toggle="collapse" data-target="#${formId}" aria-expanded="false" aria-controls="${formId}">Log Call Outcome</button>
          </div>
          <div class="collapse" id="${formId}">
              <form class="mt-3" method="POST" action="index.php?route=log-contact-interaction">
                  <input type="hidden" name="contact_id" value="${
                    contact.contact_id
                  }">
                  <input type="hidden" name="account_id" value="${
                    account.account_id
                  }"> <!-- Pass account_id -->
                  <input type="hidden" name="user_id" value="1"> <!-- Example user ID -->
                  <input type="hidden" name="target_list_id" value="${
                    account.target_list_id || ""
                  }"> <!-- Optional target list -->

                  <!-- Outcome Dropdown -->
                  <div class="form-group">
                      <label for="outcomeContact${index}">Call Outcome</label>
                      <select class="form-control" id="outcomeContact${index}" name="outcome">
                          <option value="successful">Successful</option>
                          <option value="busy">Busy</option>
                          <option value="no_answer">No Answer</option>
                          <option value="interested">Interested</option>
                          <option value="not_interested">Not Interested</option>
                      </select>
                  </div>

                  <!-- Notes -->
                  <div class="form-group">
                      <label for="notesContact${index}">Notes</label>
                      <textarea class="form-control" id="notesContact${index}" name="notes" rows="3" placeholder="Enter any notes"></textarea>
                  </div>

                  <!-- Next Contact Date -->
                  <div class="form-group">
                      <label for="nextContact${index}">Next Contact Date</label>
                      <input type="datetime-local" class="form-control" id="nextContact${index}" name="next_contact_date">
                  </div>

                  <!-- Log Interaction Button -->
                  <button type="submit" class="btn btn-primary">Log Interaction</button>
              </form>
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
