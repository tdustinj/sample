import { apiOspos } from '../config/config';

export function getActiveBrands(authorizationToken) {
   return function(dispatch, getState){
    return fetch(apiOspos.baseUrl + '/brand/allBrands', {
       method: 'POST',
       headers: {
          'Authorization': 'Bearer ' + authorizationToken,
          'Content-Type': 'application/json',
        },
     })
     .then((response) => response.json())
     .then((responseData) => {
        let brandsArray = [];

        responseData.data.forEach(function(brand){
          brandsArray.push(brand.brand_name)
        });

        brandsArray.sort();

        if(responseData.error){
            dispatch({type: "ACTIVE_BRANDS_REJECTED", payload: responseData})
        }else{
            dispatch({type: "ACTIVE_BRANDS_FULFILLED", payload: brandsArray})
        }
    })
  };
};



export function getActiveBrandsFull(authorizationToken) { //
   return function(dispatch, getState){
    return fetch(apiOspos.baseUrl + '/brand/allBrands', {
       method: 'POST',
       headers: {
          'Authorization': 'Bearer ' + authorizationToken,
          'Content-Type': 'application/json',
        },
     })
     .then((response) => response.json())
     .then((responseData) => {
       let brandsArray = [];

       responseData.data.forEach(function(brand){
         brandsArray.push(brand)
       });

       brandsArray.sort();

        if(responseData.error){
            dispatch({type: "ACTIVE_BRANDS_FULL_REJECTED", payload: responseData})
        }else{
            dispatch({type: "ACTIVE_BRANDS_FULL_FULFILLED", payload: responseData.data})
        }
    })
  };
};

export function getActiveCategories(authorizationToken) {
   return function(dispatch, getState){
    return fetch(apiOspos.baseUrl + '/category/allCategories', {
       method: 'POST',
       headers: {
          'Authorization': 'Bearer ' + authorizationToken,
          'Content-Type': 'application/json',
        },
     })
     .then((response) => response.json())
     .then((responseData) => {
        let categories = responseData.data.sort();
        if(responseData.error){
            dispatch({type: "ACTIVE_CATEGORIES_REJECTED", payload: responseData})
        }else{
            dispatch({type: "ACTIVE_CATEGORIES_FULFILLED", payload: categories})
        }
    })
  };
};

export function getActiveCategoriesFull(authorizationToken) {
   return function(dispatch, getState){
    return fetch(apiOspos.baseUrl + '/category/allCategoriesFull', {
       method: 'POST',
       headers: {
          'Authorization': 'Bearer ' + authorizationToken,
          'Content-Type': 'application/json',
        },
     })
     .then((response) => response.json())
     .then((responseData) => {
        let categories = responseData.data.sort();
        if(responseData.error){
            dispatch({type: "ACTIVE_CATEGORIES_FULL_REJECTED", payload: responseData})
        }else{
            dispatch({type: "ACTIVE_CATEGORIES_FULL_FULFILLED", payload: categories})
        }
    })
  };
};

export function storeReset() {
   return function(dispatch, getState){
      return dispatch({type: "BRAND_STORE_RESET", payload: ''})
   };
};
