import { apiUrl } from "../constants/config";
import Api from "../utils/api";

export function getApplicationList(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/application/list', params).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}

export function getApplicationCategory() {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/application/category/all').then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}

export function storeApplication(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/application/store', params, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}
