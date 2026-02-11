import './bootstrap';

import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

window.toastr = toastr;

toastr.options = {
    closeButton: false,
    progressBar: false,
    timeOut: 3000,
    extendedTimeOut: 1000,
    positionClass: "toast-top-right",
    escapeHtml: false,
}
