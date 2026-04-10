document.addEventListener('DOMContentLoaded', () => {
    const dataDiv = document.getElementById('appointmentData');
    if (!dataDiv) return;

    const did = dataDiv.dataset.did;
    const pid = dataDiv.dataset.pid;

    const form = document.getElementById('appointmentForm');
    if (form) {
        form.addEventListener('submit', e => {
            e.preventDefault();
            const formData = new FormData(form);

            fetch('book_appointment.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                const sidebar = document.getElementById('sidebar');
                const ads = document.getElementById('ads');

                if (data.success) {
                    sidebar.innerHTML = `
                        <h2>✅ Appointment Booked!</h2>
                        <p><strong>Doctor:</strong> ${data.doctor_name}</p>
                        <p><strong>Day:</strong> ${data.day}</p>
                        <p><strong>Time:</strong> ${data.time}</p>
                        <button id="cancelBtn">Cancel Appointment</button>
                    `;
                    ads.innerHTML = `
                        <h2>🎉 Confirmation</h2>
                        <p>Your appointment was successfully booked. Please be on time!</p>
                    `;
                } else {
                    sidebar.innerHTML = `<h2>❌ Booking Failed</h2><p>${data.message}</p>`;
                    ads.innerHTML = `<h2>🕒 Try Again</h2><p>Please select a different slot.</p>`;
                }

                attachCancelHandler(); // Reattach cancel button
            })
            .catch(async err => {
                const errorText = await err.text?.() || err.message || 'Unknown error';
                console.error('Booking error:', errorText);
                document.getElementById('sidebar').innerHTML = `<h2>⚠️ Error</h2><p>${errorText}</p>`;
            });

        });
    }

    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            fetch('logout.php').then(() => {
                window.location.href = 'login.html';
            });
        });
    }

    function attachCancelHandler() {
        const cancelBtn = document.getElementById('cancelBtn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => {
                if (!confirm("Are you sure you want to cancel the appointment?")) return;

                const cancelData = new URLSearchParams();
                cancelData.append('pid', pid);
                cancelData.append('did', did);

                fetch('cancel_appointment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: cancelData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert("✅ Appointment cancelled successfully!");
                        window.location.reload();
                    } else {
                        alert("❌ Cancel failed: " + (data.message || "Unknown error."));
                    }
                })
                .catch(err => {
                    console.error('Error during cancel:', err);
                    alert("⚠️ Something went wrong while cancelling.");
                });
            });
        }
    }

    attachCancelHandler();
});
