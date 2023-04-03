<link rel="icon" type="image/x-icon" href="/assets/images/favicon.png?v=1.01" />

<style>
/* ajax loader */
.loader-spin {
    position: fixed;
    top: 25%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 6rem;
    height: 6rem;
    z-index: 99999;
    display: none;
    ;
}

.rectangle-bounce {
    position: relative;
    display: flex;
    justify-content: space-between;
    width: 100%;
    height: 100%;
    transition: all 300ms ease-in-out 0s;
    z-index: 1;
}

.rectangle-bounce .rect-1,
.rectangle-bounce .rect-2,
.rectangle-bounce .rect-3,
.rectangle-bounce .rect-4,
.rectangle-bounce .rect-5 {
    width: 15%;
    height: 100%;
    background-color: #0096FF;
    background: linear-gradient(to right, #457fca, #5691c8);
    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    background: -webkit-linear-gradient(to right, #457fca, #5691c8);
    /* Chrome 10-25, Safari 5.1-6 */
    -webkit-animation: rectangle-bounce 1.5s infinite ease-in-out;
    animation: rectangle-bounce 1.5s infinite ease-in-out;
    border-radius: 100px;
}

.rectangle-bounce .rect-2 {
    -webkit-animation-delay: -1.4s;
    animation-delay: -1.4s;
}

.rectangle-bounce .rect-3 {
    -webkit-animation-delay: -1.3s;
    animation-delay: -1.3s;
}

.rectangle-bounce .rect-4 {
    -webkit-animation-delay: -1.2s;
    animation-delay: -1.2s;
}

.rectangle-bounce .rect-5 {
    -webkit-animation-delay: -1.1s;
    animation-delay: -1.1s;
}

@-webkit-keyframes rectangle-bounce {

    0%,
    40%,
    100% {
        transform: scaleY(0.4);
    }

    20% {
        transform: scaleY(1);
    }
}

@keyframes rectangle-bounce {

    0%,
    40%,
    100% {
        transform: scaleY(0.4);
    }

    20% {
        transform: scaleY(1);
    }
}

.modal-body label {
    font-weight: bold
}

.modal-body legend {
    padding-left: 0;
}

</style>
<link href="/assets/css/main.css?v=1.04" rel="stylesheet" />
<style>
.vt-col.top-left,
.vt-col.top-right {
    display: none !important;
}

</style>

<style>
@import url(https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css);

.vt-col.top-left,
.vt-col.top-right {
    display: none !important;
}

.select2-container {
    width: 100% !important;
}

.select2-selection {
    width: 100%;
    height: calc(2.25rem + 2px) !important;
    /* padding: 0.375rem 1.75rem 0.375rem 0.75rem; */
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    vertical-align: middle;
    background: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") no-repeat right 0.75rem center/8px 10px;
    background-color: #fff;
    border: 1px solid #ced4da !important;
    border-radius: 0.25rem !important;
    appearance: none !important;
    display: flex !important;
    align-items: center;
}

.select2-selection__arrow {
    display: none !important;
}

</style>
