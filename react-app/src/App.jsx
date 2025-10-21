import React, { useState } from "react";

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
    <div className="min-vh-100 d-flex align-items-center justify-content-center mt-5">
        <div className="card shadow-sm rounded-lg w-75" style={{ maxWidth: 900 }}>
            <div className="card-body p-4">
                <h3 className="card-title mb-3 text-center">Training Registration</h3>

                <form onSubmit={handleSubmit}>
                    {/* PERSONAL DATA */}
                    <section className="mb-4">
                        <div className="d-flex justify-content-between align-items-center mb-3">
                            <h6 className="mb-0">PERSONAL DATA</h6>
                            <small className="text-muted">* required fields</small>
                        </div>

                        <div className="row g-3">
                            <div className="col-md-6">
                                <label htmlFor="firstName" className="form-label">
                                    First Name <span className="text-danger">*</span>
                                </label>
                                <input
                                    id="firstName"
                                    name="firstName"
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

                            <div className="col-md-4">
                                <label htmlFor="stateOfOrigin" className="form-label">
                                    State of Origin
                                </label>
                                <input
                                    id="stateOfOrigin"
                                    name="stateOfOrigin"
                                    value={form.stateOfOrigin}
                                    onChange={handleChange}
                                    className="form-control"
                                    placeholder="e.g. Lagos"
                                />
                            </div>

                            <div className="col-md-6">
                                <label htmlFor="lga" className="form-label">
                                    LGA
                                </label>
                                <input
                                    id="lga"
                                    name="lga"
                                    value={form.lga}
                                    onChange={handleChange}
                                    className="form-control"
                                />
                            </div>

                            <div className="col-md-6">
                                <label htmlFor="phone" className="form-label">
                                    Phone Number
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

                            <div className="col-12">
                                <label htmlFor="address" className="form-label">
                                    Address
                                </label>
                                <input
                                    id="address"
                                    name="address"
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
                                    type="email"
                                    value={form.email}
                                    onChange={handleChange}
                                    className="form-control"
                                    required
                                />
                            </div>
                        </div>
                    </section>

                    {/* NEXT OF KIN */}
                    <section className="mb-4">
                        <h6 className="mb-3">NEXT OF KIN DETAILS</h6>
                        <div className="row g-3">
                            <div className="col-md-6">
                                <label htmlFor="kinName" className="form-label">
                                    Emergency Contact (Name)
                                </label>
                                <input
                                    id="kinName"
                                    name="kinName"
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
                                    value={form.kinRelationship}
                                    onChange={handleChange}
                                    className="form-control"
                                />
                            </div>

                            <div className="col-md-3">
                                <label htmlFor="kinPhone" className="form-label">
                                    Phone Number
                                </label>
                                <input
                                    id="kinPhone"
                                    name="kinPhone"
                                    value={form.kinPhone}
                                    onChange={handleChange}
                                    className="form-control"
                                />
                            </div>
                        </div>
                    </section>

                    {/* TRAINING DETAILS */}
                    <section className="mb-4">
                        <h6 className="mb-3">TRAINING DETAILS</h6>
                        <div className="row g-3">
                            <div className="col-md-6">
                                <label htmlFor="program" className="form-label">
                                    Program of Study
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
                                <label className="form-label d-block mb-2">Days of training</label>
                                <div className="d-flex flex-wrap gap-3 align-items-center">
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

                                    <div className="form-check ms-3">
                                        <input
                                            className="form-check-input"
                                            type="checkbox"
                                            id="repeatWeekly"
                                            name="repeatWeekly"
                                            checked={form.repeatWeekly}
                                            onChange={handleChange}
                                        />
                                        <label className="form-check-label" htmlFor="repeatWeekly">
                                            Repeat every week
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {/* ACKNOWLEDGEMENT */}
                    <section className="mb-3">
                        <div className="form-check border rounded p-3">
                            <input
                                className="form-check-input"
                                type="checkbox"
                                id="acknowledge"
                                name="acknowledge"
                                checked={form.acknowledge}
                                onChange={handleChange}
                            />
                            <label className="form-check-label ms-2" htmlFor="acknowledge">
                                <strong>I hereby acknowledge that</strong> — Lorem ipsum dolor sit amet,
                                consectetur adipiscing elit. I agree to abide by the rules and accept that
                                the organizers may contact me regarding the program.
                            </label>
                        </div>
                    </section>

                    <div className="text-center mt-4">
                        <button type="submit" className="btn btn-primary px-5 py-2">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
);
}
