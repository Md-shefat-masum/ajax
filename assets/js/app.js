let ajax = {
    // base: "https://qbank.techparkit.org/api",
    base: "/api",
    form_submit_type: 'POST',
    data: "",
    single_data: {},
    table: document.querySelector("#ajax_table"),
    table_body: document.querySelector("#ajax_table tbody"),
    ajax_form: document.querySelector("#ajax_form"),
    pagination_list: document.querySelector(".pagination_list"),
    formModal: new bootstrap.Modal('#formModalToggle', {}),
    detailsModal: new bootstrap.Modal('#detailsModal', {}),

    init: async function (end_point = "/api/user?page=1", serach_key = "") {
        let url = end_point;
        if (serach_key) {
            url += `&search=${serach_key}`;
        }
        let res = await axios.get(url);
        let data = res.data;
        this.data = data;
        this.render();
    },
    get_data: async function (id = 1, end_point = "/api/user", show_modal = false) {
        this.form_submit_type = 'PUT';
        let res = await fetch(end_point + `/${id}`);
        let data = await res.json();
        this.single_data = data;
        if (show_modal) {
            return this.render_show_modal();
        }
        this.render_form();
    },
    submit: async function (end_point = "/api/user") {
        this.remove_error();
        this.disable_submit_btn();
        let url = this.base + end_point;
        if (this.form_submit_type == "PUT") {
            url += `/${this.single_data.id}`;
        }

        let res = null;
        let status = null;
        let data = null;

        try {
            res = await axios["post"](url, new FormData(this.ajax_form));
            status = res.status;
            data = res.data;
        } catch (error) {
            status = error.response.status;
            data = error.response.data;
            console.log(error.response);
        }
        
        this.enable_submit_btn();

        if (status == 422) {
            return this.render_error(data)
        }
        this.alert();
        this.ajax_form.reset();
        this.init();
        this.formModal.hide();
    },
    delete: async function (id, end_point = "/user") {
        let confirm = window.confirm('delete')
        if (!confirm) {
            return 0;
        }
        let res = await fetch(this.base + end_point + `/${id}`, { method: "DELETE" });
        let status = res.status;
        let data = await res.json();
        if (status == 200) {
            this.init();
        }
    },
    disable_submit_btn: function () {
        [...document.querySelectorAll('button[type="submit"]')].forEach(el => (el.disabled = true))
    },
    enable_submit_btn: function () {
        [...document.querySelectorAll('button[type="submit"]')].forEach(el => el.disabled = false)
    },
    render_show_modal: function () {
        for (const key in this.single_data) {
            if (Object.hasOwnProperty.call(this.single_data, key)) {
                const value = this.single_data[key];
                let el = document.querySelector(`#d_${key}`);
                if (el) {
                    el.innerHTML = value;
                }
            }
        }

        let el = document.querySelector(`#d_image`)
        el?.src && (el.src = this.single_data["image"]);
        this.detailsModal.show();
    },
    render: function () {
        // console.log(this.data);
        let html = this.data.data.map(function (i) {
            return `
                <tr>
                    <td class="ps-3">${i.id}</td>
                    <td class="">${i.full_name}</td>
                    <td class="">${i.email}</td>
                    <td class="">${i.dob}</td>
                    <td class="">${i.user_role}</td>
                    <td class="">${i.gender}</td>
                    <td class="">${JSON.parse(i.courses).join(', ')}</td>
                    <td class="">${i.description}</td>
                    <td class="">
                        <img width="60px;" src="${i.image}" alt="">
                    </td>
                    <td class=" white-space-nowrap text-end pe-3">
                        <div class="font-sans-serif btn-reveal-trigger position-static">
                            <button class="btn btn-sm btn-outline-success dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent">
                                <i class="fa fa-align-right"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end py-2">
                                <a onclick="ajax.get_data(${i.id},'/api/user',true)" class="dropdown-item" href="#!">View</a>
                                <a class="dropdown-item" onclick="ajax.get_data(${i.id})" href="#!">Edit</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" onclick="ajax.delete(${i.id})" href="#!">Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
            `
        }).join('');
        this.table_body.innerHTML = html;

        let paginateHTML = this.data.links.map(function (i) {
            return `
                <li class="page-item">
                    <a class="page-link ${i.active && `active`}" onclick="event.preventDefault(); ajax.init('${i.url}')" href="${i.url}">
                        ${i.label}
                    </a>
                </li>
            `
        }).join('');
        this.pagination_list.innerHTML = paginateHTML;
    },
    render_form: function () {
        // console.log(this.single_data);
        this.ajax_form.reset();
        for (const key in this.single_data) {
            if (Object.hasOwnProperty.call(this.single_data, key)) {
                const value = this.single_data[key];
                let el = document.querySelector(`#${key}`);
                let elByName = document.querySelector(`input[name="${key}"]`);
                let courses = document.querySelectorAll(`input[name="courses[]"]`);
                if (el && el.nodeName != "LABEL") {
                    if (['text', 'textarea', 'select', 'date', 'time', 'email'].includes(el.type)) {
                        el.value = value;
                    }
                } else if (elByName) {
                    if (elByName.type == 'radio') {
                        let radioEL = document.querySelector(`input[value="${value}"]`);
                        radioEL && (radioEL.checked = true)
                    }
                } else if (courses.length && key == "courses") {
                    [...courses].forEach(function (el) {
                        if (value?.includes(el.value)) {
                            el.checked = true;
                        }
                    })
                }
            }
        }
        this.formModal.show();
    },
    remove_error: function () {
        [...document.querySelectorAll('.form_error')].forEach(el => el.remove());
    },
    render_error: function (error) {
        this.alert(error.err_message, 'error');
        for (const key in error.data) {
            if (Object.hasOwnProperty.call(error.data, key)) {
                const msg = error.data[key][0];
                document.querySelector(`#${key}`)?.parentNode.insertAdjacentHTML('beforeend', `
                    <div class="form_error text-danger">${msg}</div>
                `)
            }
        }
    },
    alert: function (title = "success", icon = "success") {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon,
            title
        })
    }
}
