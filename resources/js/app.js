import './bootstrap';
import * as FilePond from 'filepond';
import 'filepond/dist/filepond.min.css';

const inputElement = document.querySelector('input[type="file"].filepond');

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

FilePond.create(inputElement).setOptions({
    server: {
        process: '/uploads/process',
        revert: "/uploads/revert",
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        }
    }
});