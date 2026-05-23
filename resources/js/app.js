import './bootstrap';

import Swal from 'sweetalert2';
import './book-reader';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);

window.Alpine = Alpine;

Alpine.start();

window.Swal = Swal;

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

window.Swal = Swal;

window.addEventListener('swal', event => {
    Swal.fire({
        title: event.detail[0].title,
        text: event.detail[0].text,
        icon: event.detail[0].icon,
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
                id: data.id
            });
        }
    });
});
