import './bootstrap';
import Swal from 'sweetalert2';

window.Swal = Swal;
window.addEventListener('swal', event => {

    Swal.fire({
        title: event.detail.title,
        text: event.detail.text,
        icon: event.detail.icon,
        confirmButtonColor: '#2563eb',
    });

});

window.addEventListener('swal:confirm', event => {

    Swal.fire({
        title: event.detail.title,
        text: event.detail.text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, continue'
    }).then((result) => {

        if (result.isConfirmed) {
            Livewire.dispatch(event.detail.event, {
                id: event.detail.id
            });
        }

    });

});
