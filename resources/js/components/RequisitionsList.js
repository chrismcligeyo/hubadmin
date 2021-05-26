import axios from 'axios'
import React, { Component } from 'react'
import { Link } from 'react-router-dom'

class RequisitionList
    extends Component {
    constructor() {
        super()

        this.state = {
            requisitions: []
        }
    }

    componentDidMount() {
        axios.get('/api/requisitions').then(response => {
            this.setState({
                requisitions: response.data
            })
        })
    }

    mySearch() {
        // Declare variables 
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    render() {
        const { requisitions } = this.state

        return (
            <div className='container py-4'>
                <div className='row justify-content-center'>
                    <div className='col-md-12'>
                        <div className='card'>
                            <div className='card-header'>All Requisition
                <div className="active-cyan-4 mb-4 py-4">
                                    <input id="myInput" onKeyUp={this.mySearch} className="form-control" type="text" placeholder="Search" aria-label="Search"></input>
                                </div>
                            </div>



                            <div className='card-body'>
                                <table id="myTable" className="table" responsive="true">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>User</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    {requisitions.map(requisition => (


                                        <tbody key={requisition.id}>
                                            <tr >

                                                {requisition.status == 0 ? null :
                                                    <React.Fragment>
                                                        <td>#{requisition.code}</td>
                                                        <td>
                                                            <Link
                                                                to={`/${requisition.id}`}
                                                            >
                                                                {requisition.name}
                                                            </Link>
                                                        </td>
                                                        <td>{requisition.date}</td>
                                                        <td>{requisition.user.first_name}</td>
                                                        <td>
                                                            {requisition.status == 1 ? <span className='badge badge-primary badge-pill'>
                                                                Pending
                                                            </span> : requisition.status == 2 ? <span className='badge badge-success badge-pill'>
                                                                                                            Approved
                                                            </span> : requisition.status == 3 ? <span className='badge badge-danger badge-pill'>
                                                                                                                Declined
                                                            </span> : null}
                                                        </td>
                                                    </React.Fragment>
                                                }
                                            </tr>
                                        </tbody>
                                    ))}
                                </table>
                                {/* <ul className='list-group list-group-flush'>
                  {requisitions.map(requisition => (
                    <Link
                      className='list-group-item list-group-item-action d-flex justify-content-between align-items-center'
                      to={`/${requisition.id}`}
                      key={requisition.id}
                    >
                      {requisition.name}
                    </Link>
                  ))}
                </ul> */}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

export default RequisitionList

