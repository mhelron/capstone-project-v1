// Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyARhHx8V5F3mILZujmFXVEwZtJbUMvrHVQ",
    authDomain: "capstone-project-v1-ddf72.firebaseapp.com",
    databaseURL: "https://capstone-project-v1-ddf72-default-rtdb.firebaseio.com",
    projectId: "capstone-project-v1-ddf72",
    storageBucket: "capstone-project-v1-ddf72.appspot.com",
    messagingSenderId: "831745544682",
    appId: "1:831745544682:web:c2d48183143aaff9682293",
    measurementId: "G-GWDDD0KKBH"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Reference to Firebase Realtime Database
const database = firebase.database();

// Real-time listener for changes to the 'users' data
const usersRef = database.ref('users');
usersRef.on('value', (snapshot) => {
    const usersData = snapshot.val();
    const usersTableBody = document.querySelector('table tbody');
    usersTableBody.innerHTML = ''; // Clear the current rows

    if (usersData) {
        let i = 1;
        for (const [key, user] of Object.entries(usersData)) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${i++}</td>
                <td>${user.fname}</td>
                <td>${user.lname}</td>
                <td>${user.email}</td>
                <td>${user.user_role}</td>
                <td>
                    <div class="d-flex">
                        <a href="/admin/users/edit-user/${key}" class="btn btn-sm btn-success me-2">Edit</a>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#archiveModal" data-id="${key}" data-name="${user.fname} ${user.lname}">Archive</button>
                    </div>
                </td>
            `;
            usersTableBody.appendChild(row);
        }
    } else {
        const emptyRow = document.createElement('tr');
        emptyRow.innerHTML = `<td colspan="7">No Records Found</td>`;
        usersTableBody.appendChild(emptyRow);
    }
});