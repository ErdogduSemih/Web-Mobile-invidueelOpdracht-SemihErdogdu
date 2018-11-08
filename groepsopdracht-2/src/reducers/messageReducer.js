import * as types from "../actions/actionTypes";
import initialState from "../store/initialState";

export default function messageReducer(state = initialState.message, action) {
    switch (action.type) {
        case types.UPVOTE_MESSAGE: {
            return Object.assign({}, state, action.body);
        }

        case types.DOWNVOTE_MESSAGE: {
            return 
        }

        default:
            return state;
    }
}