import { apiUrl } from "../constants/config";
import Api from "../utils/api";

export function getQuoteList(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/quote/list', params).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}
