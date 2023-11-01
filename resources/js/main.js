import { router } from "@inertiajs/vue3";

// Function to slugify
function slugify(str) {
    console.log(str);
    return str
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, "")
        .replace(/[\s_-]+/g, "-")
        .replace(/^-+|-+$/g, "");
}
// // Function to create slug from name
// function changeNameToSlug(name) {
//     return this.slugify(name);
// }
// Function to create slug
function changeToSlug(name = "", slug = "") {
    if (slug !== "") {
        return slugify(slug);
    } else {
        return slugify(name);
    }
}
function createRequest({
    url,
    data,
    state_preservation = true,
    scroll_preservation = true,
    only_list,
} = {}) {
    router.post(url, data, {
        preserveState: state_preservation,
        preserveScroll: scroll_preservation,
        only: only_list,
    });
}

function editRequest({
    url,
    data,
    dataId,
    state_preservation = true,
    scroll_preservation = true,
    only_list,
} = {}) {
    // data = JSON.parse(data);
    data['_method'] = "put";
    console.log(data);
    router.post(url + (dataId ?? ''), data, {
        preserveState: state_preservation,
        preserveScroll: scroll_preservation,
        only: only_list,
    });
}

function deleteRequest({
    url,
    dataId,
    state_preservation = true,
    scroll_preservation = true,
    only_list,
} = {}) {
    router.delete(url + (dataId ?? ''), {
        preserveState: state_preservation,
        preserveScroll: scroll_preservation,
        only: only_list,
    });
}

function addToCart({
    url = '/cart/add',
    product,
    type,
    quantity = 1,
    state_preservation = true,
    scroll_preservation = true,
    only_list,
} = {}) {
    // this.added = true;
    var itemToCart = {};
    itemToCart.product = product;
    itemToCart.quantity = quantity;
    itemToCart.type = type;
    router.post(url, itemToCart, {
        preserveState: state_preservation,
        preserveScroll: scroll_preservation,
        only: only_list,
    })
  }

  function payNow({
    url = '/subscription-plan/checkout',
    product,
    type,
    quantity = 1,
    state_preservation = true,
    scroll_preservation = true,
    only_list,
} = {}) {
    // this.added = true;
    var itemToPay = {};
    itemToPay.product = product;
    itemToPay.quantity = quantity;
    itemToPay.type = type;
    router.post(url, itemToPay, {
        preserveState: state_preservation,
        preserveScroll: scroll_preservation,
        only: only_list,
    })
  }

export { changeToSlug, createRequest, editRequest, deleteRequest, addToCart, payNow };
