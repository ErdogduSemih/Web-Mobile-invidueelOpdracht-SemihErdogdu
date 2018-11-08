import * as types from './actionTypes';

function  messageUpvoted(message){
    return {
        type: 'UPVOTE_MESSAGE',
        body: message
    };
}

function  messageDownvoted(message){
    return {
        type: 'DOWNVOTE_MESSAGE',
        body: message
    };
}


export function storeMessages(message) {
    return function (dispatch) {
        return dispatch( messageDownvoted(message));
    };
}