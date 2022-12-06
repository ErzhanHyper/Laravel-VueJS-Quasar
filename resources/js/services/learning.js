import { apiUrl } from "../constants/config";
import Api from "../utils/api";

export function getLearningCatalog(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/learning/catalog', params).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}

export function getLearningCatalogDetail(id) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/learning/catalog/' + id + '/get').then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}

export function storeLearning(params) {
    return new Promise((resolve, reject) => {
        Api.post('/learning/store', params, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject(e.response.data)
        })
    })
}

export function storeLearningCatalog(params) {
    return new Promise((resolve, reject) => {
        Api.post('/learning/catalog/store', params).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject(e.response.data)
        })
    })
}

export function storeLearningDetailViewer(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/learning/viewer/store', params).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject(e.response.data)
        })
    })
}

