import { apiUrl } from "../constants/config";
import Api from "../utils/api";

export function getGalleryList(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/gallery/catalog', params).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}

export function getGalleryData(id) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/gallery/'+ id +'/get').then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}
