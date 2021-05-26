import axios from 'axios';
import React, { Component } from 'react';
import jsPDF from 'jspdf';
import html2canvas from 'html2canvas';

class SingleRequisition extends Component {
    constructor(props) {
        super(props)

        this.state = {
            requisition: {},
            requisitionitems: [],
            requisitiontotals: [],
            itemname: '',
            errors: []
        }

        this.handleFieldChange = this.handleFieldChange.bind(this)

        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
        this.handleMarkRequisitionAsApproved = this.handleMarkRequisitionAsApproved.bind(this)
        this._exportPdf = this._exportPdf.bind(this)
        // this.handleMarkRequisitionAsDeclined = this.handleMarkRequisitionAsDeclined.bind(this)
    }

    componentDidMount() {
        //  const requisitionId = this.props.match.params.id

        axios.get(`/api/requisitions/${this.props.match.params.id}`).then(response => {
            this.setState({
                requisition: response.data.requisition,
                requisitionitems: response.data.requisitionitems,
                requisitiontotals: response.data.requisitiontotals
            })
        })
    }

    handleFieldChange(event) {
        this.setState({
            itemname: event.target.value
        })
    }



    hasErrorFor(field) {
        return !!this.state.errors[field]
    }

    renderErrorFor(field) {
        if (this.hasErrorFor(field)) {
            return (
                <span className='invalid-feedback'>
                    <strong>{this.state.errors[field][0]}</strong>
                </span>
            )
        }
    }

    handleMarkRequisitionAsApproved() {
        const { history } = this.props

        axios
            .put(`/api/requisitions/approve/${this.state.requisition.id}`)
            .then(response => history.push('/'))

    }

    _exportPdf() {
        const { history } = this.props

        axios
            .get(`/api/print`)
            .then(response => history.push('/'))
    }

    // _exportPdf() {

    //     // html2canvas(document.querySelector("#capture")).then(canvas => {
    //     //     document.body.appendChild(canvas);  // if you want see your screenshot in body.
    //     //     const imgData = canvas.toDataURL('image/png');
    //     //     const pdf = new jsPDF();
    //     //     pdf.addImage(imgData, 'PNG', 0, 30, 0, 99);
    //     //     pdf.save("requisition.pdf");
    //     // });

        
    //     const { history } = this.props
    //     axios
    //         .get(`/api/print`)
    //         .then(response => history.push('/'))

    // }
    


    render() {
        const { requisition, requisitionitems, requisitiontotals } = this.state

        return (

            <div id="capture" className='container py-4'>
                <div className='row justify-content-center'>
                    <div className='col-md-12'>
                        <div className='card '>
                        <div className='card-header'>Requisition #{requisition.code}</div>
                        <div className='row'>
                                <div className='col-sm m-4'>
                                    User: <b className='m-2'>{requisition.user && requisition.user.first_name}</b><br/>
                                    Phone: <b className='m-2'>{requisition.user && requisition.user.phone}</b>
                                </div>

                                <div className='col-sm m-4'>
                                    Requisition Name: <b className='m-2'>{requisition.name}</b><br />
                                    Activation: <b className='m-2'>{requisition.activation && requisition.activation.name}</b>
                                </div>

                                <div className='col-sm m-4'>
                                    {requisitiontotals.map(requisitiontotal => (
                                        <p className='text-right' key={requisition.id}>Total Amount: <b>{requisitiontotal.total}</b></p>
                                    ))}
                                </div>
                        </div>

                            <div className='card-body '>
                                
                                
                                <p >Description: {requisition.description}</p>
                                {requisition.status == 2 ? <button
                                    className='btn btn-primary btn-sm m-4'
                                    onClick={this._exportPdf}
                                >
                                    Print Requisition
              </button> :
                                    <React.Fragment>
                                        <button
                                            className='btn btn-primary btn-sm m-4'
                                            onClick={this.handleMarkRequisitionAsApproved}
                                        >
                                            Approve
              </button>

                                        {/* <button
                                            className='btn btn-danger btn-sm'
                                            onClick={this.handleMarkRequisitionAsDeclined}
                                        >
                                            Decline
              </button> */}
                                    </React.Fragment>
                                }



                                <hr />

                                <table id="table" className="table" responsive="true">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Supplier</th>
                                            <th>Unit Cost</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    {requisitionitems.map(requisitionitem => (


                                        <tbody key={requisitionitem.id}>
                                            <tr >
                                                <td>{requisitionitem.itemname}</td>
                                                <td>{requisitionitem.supplier}</td>
                                                <td>{requisitionitem.unit_cost}</td>
                                                <td>{requisitionitem.quantity}</td>
                                                <td>{requisitionitem.total}</td>
                                            </tr>
                                        </tbody>
                                    ))}
                                </table>

                                {requisitiontotals.map(requisitiontotal => (
                                    <b className='right' key={requisition.id}>Total: {requisitiontotal.total}</b>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )

    }
}

export default SingleRequisition


