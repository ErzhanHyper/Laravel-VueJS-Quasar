import { apiUrl } from "../constants/config";
import Api from "../utils/api";

export function getFeedList(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/feed/list', params).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}

export function getFeedCategory(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/feed/category', params).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}

export function getFeedDetail(id) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/feed/'+id+'/get').then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}

export function saveFeedDetail(id, params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/feed/'+id+'/update', params, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject(e.response.data)
        })
    })
}

export function storeFeed(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/feed/store', params, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject(e.response.data)
        })
    })
}
