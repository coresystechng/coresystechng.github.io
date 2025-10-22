import React, { useState, useEffect } from "react";
import { getLGAs } from "./lgas";
import Footer from "./footer.jsx";
import Header from "./header.jsx";

const programs = [
    "Basic Computer Skills",
    "Microsoft Office Suite",
    "Graphic Design Basics",
    "Frontend Development",
    "Backend Development",
    "Fullstack Development",
];

export default function App() {
  const [form, setForm] = useState({
    firstName: "",
    lastName: "",
    gender: "",
    maritalStatus: "",
    stateOfOrigin: "",
    lga: "",
    address: "",
    phone: "",
    email: "",
    passport: null,
    kinName: "",
    kinRelationship: "",
    kinPhone: "",
    program: "",
    days: {
        mon: false,
        tue: false,
        wed: false,
        thu: false,
        fri: false,
    },
    repeatWeekly: false,
    acknowledge: false,
});

    const [lgaOptions, setLgaOptions] = useState([]);

    // when user selects a state, update the form and refresh the LGA options
    function handleStateChange(e) {
        const state = e.target.value;
        setForm((s) => ({ ...s, stateOfOrigin: state, lga: "" })); // reset lga
        const options = getLGAs(state);
        setLgaOptions(options);
    }

    // keep LGA options in sync if stateOfOrigin changes elsewhere
    useEffect(() => {
        if (form.stateOfOrigin) {
            setLgaOptions(getLGAs(form.stateOfOrigin));
        } else {
            setLgaOptions([]);
        }
    }, [form.stateOfOrigin]);

function handleChange(e) {
    const { name, value, type, checked } = e.target;
    if (name in form.days) {
        setForm((s) => ({ ...s, days: { ...s.days, [name]: checked } }));
        return;
    }
    if (type === "checkbox") {
        setForm((s) => ({ ...s, [name]: checked }));
        return;
    }
    setForm((s) => ({ ...s, [name]: value }));
}

function handleSubmit(e) {
    e.preventDefault();
    if (!form.acknowledge) {
        alert("Please acknowledge the terms to continue.");
        return;
    }
    // Replace with real submit (API call) as needed
    console.log("Register payload:", form);
    alert("Registration submitted — check console for payload.");
}

return (
    <div>
        <Header />
        <div className="d-flex align-items-center justify-content-center border-bottom bg-light py-5" style={{ marginTop: '5rem' }}>
            <div className="card shadow-sm rounded-lg" style={{ maxWidth: 900 , width: '90%' }}>
                <div className="card-body p-4">
                    <h3 className="card-title mb-3 text-center pt-4 pb-4 bluey
                    ">REGISTRATION FORM</h3>

                    <form onSubmit={handleSubmit}>
                        {/* PERSONAL DATA */}
                        <section className="mb-4">
                            <div className="d-flex justify-content-between align-items-center mb-3">
                                <h5 className="mb-0 bluey">1. PERSONAL DATA</h5>
                            </div>

                            <div className="row g-3 my-3 mx-lg-5">
                                <div className="col-md-6">
                                    <label htmlFor="firstName" className="form-label">
                                        First Name <span className="text-danger">*</span>
                                    </label>
                                    <input
                                        id="firstName"
                                        name="firstName"
                                        placeholder="First Name"
                                        value={form.firstName}
                                        onChange={handleChange}
                                        className="form-control"
                                        required
                                        />
                                </div>

                                <div className="col-md-6">
                                    <label htmlFor="lastName" className="form-label">
                                        Last Name <span className="text-danger">*</span>
                                    </label>
                                    <input
                                        id="lastName"
                                        name="lastName"
                                        placeholder="Last Name"
                                        value={form.lastName}
                                        onChange={handleChange}
                                        className="form-control"
                                        required
                                    />
                                </div>

                                <div className="col-md-4">
                                    <label htmlFor="gender" className="form-label">
                                        Gender
                                    </label>
                                    <select
                                        id="gender"
                                        name="gender"
                                        value={form.gender}
                                        onChange={handleChange}
                                        className="form-select"
                                    >
                                        <option value="">Select</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                        <option>Other</option>
                                    </select>
                                </div>

                                <div className="col-md-4">
                                    <label htmlFor="phone" className="form-label">
                                        Phone Number <span className="text-danger">*</span>
                                    </label>
                                    <input
                                        id="phone"
                                        name="phone"
                                        value={form.phone}
                                        onChange={handleChange}
                                        className="form-control"
                                        placeholder="+234..."
                                    />
                                </div>

                                <div className="col-md-4">
                                    <label htmlFor="maritalStatus" className="form-label">
                                        Marital Status
                                    </label>
                                    <select
                                        id="maritalStatus"
                                        name="maritalStatus"
                                        value={form.maritalStatus}
                                        onChange={handleChange}
                                        className="form-select"
                                    >
                                        <option value="">Select</option>
                                        <option>Single</option>
                                        <option>Married</option>
                                        <option>Divorced</option>
                                        <option>Widowed</option>
                                    </select>
                                </div>

                                <div className="col-md-6">
                                    <label htmlFor="stateOfOrigin" className="form-label">
                                    State of Origin <span className="text-danger">*</span>
                                    </label>

                                    <select
                                    id="stateOfOrigin"
                                    name="stateOfOrigin"
                                    value={form.stateOfOrigin}
                                    onChange={handleStateChange}
                                    className="form-select"
                                    required
                                    >
                                    <option value="">Select State of Origin</option>
                                    <option>Abia</option>
                                    <option>Adamawa</option>
                                    <option>Akwa Ibom</option>
                                    <option>Anambra</option>
                                    <option>Bauchi</option>
                                    <option>Bayelsa</option>
                                    <option>Benue</option>
                                    <option>Borno</option>
                                    <option>Cross River</option>
                                    <option>Delta</option>
                                    <option>Ebonyi</option>
                                    <option>Edo</option>
                                    <option>Ekiti</option>
                                    <option>Enugu</option>
                                    <option>FCT - Abuja</option>
                                    <option>Gombe</option>
                                    <option>Imo</option>
                                    <option>Jigawa</option>
                                    <option>Kaduna</option>
                                    <option>Kano</option>
                                    <option>Katsina</option>
                                    <option>Kebbi</option>
                                    <option>Kogi</option>
                                    <option>Kwara</option>
                                    <option>Lagos</option>
                                    <option>Nasarawa</option>
                                    <option>Niger</option>
                                    <option>Ogun</option>
                                    <option>Ondo</option>
                                    <option>Osun</option>
                                    <option>Oyo</option>
                                    <option>Plateau</option>
                                    <option>Rivers</option>
                                    <option>Sokoto</option>
                                    <option>Taraba</option>
                                    <option>Yobe</option>
                                    <option>Zamfara</option>
                                    </select>
                                </div>

                                <div className="col-md-6">
                                    <label htmlFor="lga" className="form-label">
                                    LGA <span className="text-danger">*</span>
                                    </label>

                                    <select
                                    id="lga"
                                    name="lga"
                                    value={form.lga}
                                    onChange={handleChange}
                                    className="form-select"
                                    required
                                    disabled={!form.stateOfOrigin}
                                    >
                                    <option value="">{form.stateOfOrigin ? "Select LGA" : "Choose state first"}</option>

                                    {/* map options from the LGAs object; lgaOptions is derived in handleStateChange or computed below */}
                                    {lgaOptions.map((lgaName) => (
                                        <option key={lgaName} value={lgaName}>
                                        {lgaName}
                                        </option>
                                    ))}
                                    </select>
                                </div>


                                <div className="col-12">
                                    <label htmlFor="address" className="form-label">
                                        Address
                                    </label>
                                    <input
                                        id="address"
                                        name="address"
                                        placeholder="Home Address"
                                        value={form.address}
                                        onChange={handleChange}
                                        className="form-control"
                                    />
                                </div>

                                <div className="col-md-6">
                                    <label htmlFor="email" className="form-label">
                                        Email <span className="text-danger">*</span>
                                    </label>
                                    <input
                                        id="email"
                                        name="email"
                                        placeholder="name@example.com"
                                        type="email"
                                        value={form.email}
                                        onChange={handleChange}
                                        className="form-control"
                                        required
                                    />
                                </div>

                                <div className="col-md-6">
                                    <label htmlFor="email" className="form-label">
                                        Passport <span className="text-danger">*</span>
                                    </label>
                                    {/* upload file */}
                                    <input
                                        id="passport"
                                        name="passport"
                                        type="file"
                                        accept="image/*"
                                        className="form-control"
                                        required
                                    />
                                </div>
                            </div>
                        </section>

                        {/* NEXT OF KIN */}
                        <section className="mb-4">
                            <h5 className="mb-3 bluey">2. NEXT OF KIN DETAILS</h5>
                            <div className="row g-3 my-3 mx-lg-5">
                                <div className="col-md-6">
                                    <label htmlFor="kinName" className="form-label">
                                        Emergency Contact <span className="text-danger">*</span>
                                    </label>
                                    <input
                                        id="kinName"
                                        name="kinName"
                                        placeholder="Full Name"
                                        value={form.kinName}
                                        onChange={handleChange}
                                        className="form-control"
                                    />
                                </div>

                                <div className="col-md-3">
                                    <label htmlFor="kinRelationship" className="form-label">
                                        Relationship 
                                    </label>
                                    <input
                                        id="kinRelationship"
                                        name="kinRelationship"
                                        placeholder="e.g. Father, Sister"
                                        value={form.kinRelationship}
                                        onChange={handleChange}
                                        className="form-control"
                                    />
                                </div>

                                <div className="col-md-3">
                                    <label htmlFor="kinPhone" className="form-label">
                                        Phone Number <span className="text-danger">*</span>
                                    </label>
                                    <input
                                        id="kinPhone"
                                        name="kinPhone"
                                        placeholder="+234..."
                                        value={form.kinPhone}
                                        onChange={handleChange}
                                        className="form-control"
                                    />
                                </div>
                            </div>
                        </section>

                        <section className="mb-4">
                            <h5 className="mb-3 bluey">3. TRAINING DETAILS</h5>
                            <div className="row g-3 mt-3 mb-4 mx-lg-5">
                                <div className="col-12 col-md-6">
                                    <label htmlFor="program" className="form-label">
                                        Program of Study <span className="text-danger">*</span>
                                    </label>
                                    <select
                                        id="program"
                                        name="program"
                                        value={form.program}
                                        onChange={handleChange}
                                        className="form-select"
                                    >
                                        <option value="">Select a program</option>
                                        {programs.map((p) => (
                                            <option key={p} value={p}>
                                                {p}
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div className="col-12">
                                    <label className="form-label d-block mb-2">
                                        Days of training <span className="text-danger">*</span>
                                    </label>

                                    {/* Use responsive grid so each checkbox stacks on mobile (col-12) and lays out in columns on larger screens */}
                                    <div className="row g-2">
                                        <div className="col-12 col-sm-6 col-md-auto">
                                            <div className="form-check">
                                                <input
                                                    className="form-check-input"
                                                    type="checkbox"
                                                    id="mon"
                                                    name="mon"
                                                    checked={form.days.mon}
                                                    onChange={handleChange}
                                                />
                                                <label className="form-check-label" htmlFor="mon">
                                                    Monday
                                                </label>
                                            </div>
                                        </div>

                                        <div className="col-12 col-sm-6 col-md-auto">
                                            <div className="form-check">
                                                <input
                                                    className="form-check-input"
                                                    type="checkbox"
                                                    id="tue"
                                                    name="tue"
                                                    checked={form.days.tue}
                                                    onChange={handleChange}
                                                />
                                                <label className="form-check-label" htmlFor="tue">
                                                    Tuesday
                                                </label>
                                            </div>
                                        </div>

                                        <div className="col-12 col-sm-6 col-md-auto">
                                            <div className="form-check">
                                                <input
                                                    className="form-check-input"
                                                    type="checkbox"
                                                    id="wed"
                                                    name="wed"
                                                    checked={form.days.wed}
                                                    onChange={handleChange}
                                                />
                                                <label className="form-check-label" htmlFor="wed">
                                                    Wednesday
                                                </label>
                                            </div>
                                        </div>

                                        <div className="col-12 col-sm-6 col-md-auto">
                                            <div className="form-check">
                                                <input
                                                    className="form-check-input"
                                                    type="checkbox"
                                                    id="thu"
                                                    name="thu"
                                                    checked={form.days.thu}
                                                    onChange={handleChange}
                                                />
                                                <label className="form-check-label" htmlFor="thu">
                                                    Thursday
                                                </label>
                                            </div>
                                        </div>

                                        <div className="col-12 col-sm-6 col-md-auto">
                                            <div className="form-check">
                                                <input
                                                    className="form-check-input"
                                                    type="checkbox"
                                                    id="fri"
                                                    name="fri"
                                                    checked={form.days.fri}
                                                    onChange={handleChange}
                                                />
                                                <label className="form-check-label" htmlFor="fri">
                                                    Friday
                                                </label>
                                            </div>
                                        </div>

                                        <div className="col-12 col-sm-6 col-md-auto">
                                            <div className="form-check">
                                                <input
                                                    className="form-check-input"
                                                    type="checkbox"
                                                    id="repeatWeekly"
                                                    name="repeatWeekly"
                                                    checked={form.repeatWeekly}
                                                    onChange={handleChange}
                                                />
                                                <label className="form-check-label" htmlFor="repeatWeekly">
                                                    Every day of the week
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section className="mb-1">
                            <h5 className="mb-3 bluey">4. ACKNOWLEDGEMENT</h5>
                            <div className="form-check border ps-5 pe-4 px-lg-5 py-3 mb-3">
                                <input
                                    className="form-check-input"
                                    type="checkbox"
                                    id="acknowledge"
                                    name="acknowledge"
                                    checked={form.acknowledge}
                                    onChange={handleChange}
                                />
                                <label className="form-check-label ms-2" htmlFor="acknowledge">
                                    I hereby acknowledge that the information provided above are accurate, which contain my personal information and details about the training I am to receive.
                                </label>
                            </div>
                            <small className="text-muted"><span className="text-danger"><strong>*</strong></span> required fields</small>
                        </section>

                        <div className="text-center mt-4">
                            <button type="submit" className="btn btn-secondary px-5 py-2">
                                Register
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <Footer />
    </div>
);
}
