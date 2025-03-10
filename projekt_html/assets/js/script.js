class BookingForm {
    constructor() {
        this.form = document.getElementById('booking-form');
        this.initDatePickers();
        this.initValidation();
        this.handleSubmit();
    }

    initDatePickers() {
        flatpickr('#check-in', {
            minDate: 'today',
            onChange: (dates) => {
                this.updateAvailability(dates[0]);
            }
        });
    }

    async checkAvailability(checkIn, checkOut) {
        try {
            const response = await fetch('/api/check-availability', {
                method: 'POST',
                body: JSON.stringify({ checkIn, checkOut })
            });
            return await response.json();
        } catch (error) {
            console.error('Hiba:', error);
        }
    }

    async handleSubmit() {
        this.form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (await this.validateForm()) {
                const formData = new FormData(this.form);
                try {
                    const response = await fetch('/api/create-booking', {
                        method: 'POST',
                        body: formData
                    });
                    
                    if (response.ok) {
                        this.redirectToPayment(await response.json());
                    }
                } catch (error) {
                    console.error('Hiba:', error);
                }
            }
        });
    }
}