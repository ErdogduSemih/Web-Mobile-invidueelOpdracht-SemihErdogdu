import { combineReducers } from "redux";

import messageInfo from './messageReducer';

const rootReducer = combineReducers({
    messageInfo
});

export default rootReducer;