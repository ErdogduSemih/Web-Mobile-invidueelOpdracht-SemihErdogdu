import React from 'react';
import FormControl from '@material-ui/core/FormControl';
import Select from '@material-ui/core/Select';
import MenuItem from '@material-ui/core/MenuItem';
import Paper from '@material-ui/core/Paper';
import { withStyles } from '@material-ui/core/styles';
import InputLabel from '@material-ui/core/InputLabel';
import SearchBox from './SearchBox'
import Typography from '@material-ui/core/Typography';

const styles = theme => ({
    root: {
        width: '90%',
        marginTop: theme.spacing.unit * 3,
        overflowX: 'auto',
        marginLeft: 'auto',
        marginRight: 'auto',
        padding: '20px',
    },
    formControl: {
        margin: theme.spacing.unit,
        minWidth: 120,
    },
    selectEmpty: {
        marginTop: theme.spacing.unit * 2,
    },
});

const Search = (props) => {

    let categories = [];


    props.messages.forEach(function (message) {
        categories.push(message.category);
    });

    categories = Array.from(new Set(categories));
    const { classes } = props;

    return (
        <Paper className={props.classes.root}>
            <div>
            <Typography variant="h5">
                    Search messages
                </Typography>
                <div className="mdl-grid">
                    <SearchBox placeholder={"Id..."} value={props.searchId} onChange={props.onSearchIdChange} />
                </div>
                <div className="mdl-grid">
                    <SearchBox placeholder={"Content..."} value={props.searhTerm} onChange={props.onSearchTermChange} />
                </div>
                <div className="mdl-grid">
                    <FormControl className={classes.formControl}>
                        <InputLabel htmlFor="categories">Category</InputLabel>
                        <Select
                            value={props.searchCategory}
                            id="categorySelect"
                            name="categories"
                            onChange={props.onSearchCategoryChange}>
                            <MenuItem value="">
                                <em>None</em>
                            </MenuItem>
                            {categories.map((category) => <MenuItem key={category} value={category}>{category}</MenuItem>)}
                        </Select>
                    </FormControl>
                </div>
            </div>
        </Paper >
    )
}

Search.propTypes = {

}


export default withStyles(styles)(Search);