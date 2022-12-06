import { apiUrl } from "../constants/config";
import Api from "../utils/api";

export function getNewsList(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/news/all', params).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}


export function getNewsDetail(id) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/news/'+id+'/get').then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}

export function saveNewsDetail(id, params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/news/'+id+'/update', params, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject(e.response.data)
        })
    })
}

export function storeNews(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/news/store', params, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject(e.response.data)
        })
    })
}

export function deleteNews(id) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/news/'+id+'/delete', {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject(e.response.data)
        })
    })
}
