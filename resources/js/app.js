import './bootstrap';

import Swal from 'sweetalert2';
import './book-reader';



window.addEventListener('swal', event => {
    const data = event.detail[0];

    Swal.fire({
        title: data.title,
        text: data.text,
        icon: data.icon,
        confirmButtonColor: '#2563eb',
    });
});

window.addEventListener('swal:confirm', event => {
    const data = event.detail[0];

    Swal.fire({
        title: data.title,
        text: data.text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it',
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch(data.event, {
                id: data.id,
            });
        }
    });
});