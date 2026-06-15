export default function CompanyLocationsSection({
    data,
    setData,
    saveLocations,
}) {
    const addLocation = () => {
        setData("locations", [
            ...data.locations,
            {
                location_name: "",
                location_type: "factory",
                country_name: "Indonesia",
                province_name: "",
                city_name: "",
                address: "",
                contact_person: "",
                phone: "",
                email: "",
                is_primary: false,
            },
        ]);
    };

    const updateLocation = (index, field, value) => {
        const updated = [...data.locations];

        updated[index] = {
            ...updated[index],
            [field]: value,
        };

        setData("locations", updated);
    };

    const setPrimaryLocation = (index) => {
        const updated = [...data.locations];

        updated.forEach((location) => {
            location.is_primary = false;
        });

        updated[index].is_primary = true;

        setData("locations", updated);
    };

    const removeLocation = (index) => {
        const location = data.locations[index];

        if (location.is_primary) {
            alert(
                "Primary location cannot be deleted. Please assign another primary location first.",
            );
            return;
        }

        const updated = [...data.locations];

        updated.splice(index, 1);

        setData("locations", updated);
    };

    return (
        <div
            className="
                rounded-[32px]
                border border-white/10
                bg-white/5
                backdrop-blur-xl
                p-8
            "
        >
            {/* HEADER */}

            <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <h2 className="text-xl font-black text-white uppercase">
                        Company Locations
                    </h2>

                    <p className="text-gray-400 text-sm mt-2">
                        Manage head offices, factories, warehouses, branch
                        offices, and operational facilities.
                    </p>
                </div>

                <button
                    type="button"
                    onClick={addLocation}
                    className="
                        px-6
                        py-3
                        rounded-full
                        bg-yellow-500
                        text-black
                        font-black
                        uppercase
                        text-xs
                        tracking-widest
                    "
                >
                    + Add Location
                </button>
            </div>

            {/* LOCATIONS */}

            <div className="space-y-6">
                {data.locations.map((location, index) => (
                    <div
                        key={index}
                        className="
                            rounded-[24px]
                            border border-white/10
                            bg-white/5
                            p-6
                        "
                    >
                        {/* TITLE */}

                        <div className="flex items-center justify-between mb-6">
                            <div>
                                <h3 className="text-white font-black uppercase">
                                    {location.location_name ||
                                        `Location ${index + 1}`}
                                </h3>

                                <p className="text-xs text-gray-500 uppercase mt-1">
                                    {location.location_type}
                                </p>
                            </div>

                            <button
                                type="button"
                                onClick={() => removeLocation(index)}
                                className="
                                    text-red-400
                                    text-xs
                                    font-bold
                                    uppercase
                                "
                            >
                                Delete
                            </button>
                        </div>

                        {/* PRIMARY */}

                        <div className="mb-6">
                            <label className="flex items-center gap-3 text-gray-300 text-sm">
                                <input
                                    type="radio"
                                    checked={location.is_primary}
                                    onChange={() => setPrimaryLocation(index)}
                                />
                                Primary Location
                            </label>
                        </div>

                        {/* FORM */}

                        <div className="grid md:grid-cols-2 gap-5">
                            {/* NAME */}

                            <div>
                                <label className="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                    Location Name
                                </label>

                                <input
                                    type="text"
                                    value={location.location_name || ""}
                                    onChange={(e) =>
                                        updateLocation(
                                            index,
                                            "location_name",
                                            e.target.value,
                                        )
                                    }
                                    placeholder="Factory Surabaya"
                                    className="
                                        w-full
                                        rounded-xl
                                        border border-white/10
                                        bg-black/20
                                        px-4
                                        py-3
                                        text-white
                                    "
                                />
                            </div>

                            {/* TYPE */}

                            <div>
                                <label className="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                    Location Type
                                </label>

                                <select
                                    value={location.location_type || "factory"}
                                    onChange={(e) =>
                                        updateLocation(
                                            index,
                                            "location_type",
                                            e.target.value,
                                        )
                                    }
                                    className="
                                        w-full
                                        rounded-xl
                                        border border-white/10
                                        bg-black/20
                                        px-4
                                        py-3
                                        text-white
                                    "
                                >
                                    <option value="head_office">
                                        Head Office
                                    </option>

                                    <option value="factory">Factory</option>

                                    <option value="warehouse">Warehouse</option>

                                    <option value="branch_office">
                                        Branch Office
                                    </option>

                                    <option value="research_center">
                                        Research Center
                                    </option>
                                </select>
                            </div>

                            {/* CITY */}

                            <div>
                                <label className="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                    City
                                </label>

                                <input
                                    type="text"
                                    value={location.city_name || ""}
                                    onChange={(e) =>
                                        updateLocation(
                                            index,
                                            "city_name",
                                            e.target.value,
                                        )
                                    }
                                    className="
                                        w-full
                                        rounded-xl
                                        border border-white/10
                                        bg-black/20
                                        px-4
                                        py-3
                                        text-white
                                    "
                                />
                            </div>

                            {/* CONTACT */}

                            <div>
                                <label className="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                    Contact Person
                                </label>

                                <input
                                    type="text"
                                    value={location.contact_person || ""}
                                    onChange={(e) =>
                                        updateLocation(
                                            index,
                                            "contact_person",
                                            e.target.value,
                                        )
                                    }
                                    className="
                                        w-full
                                        rounded-xl
                                        border border-white/10
                                        bg-black/20
                                        px-4
                                        py-3
                                        text-white
                                    "
                                />
                            </div>

                            {/* PHONE */}

                            <div>
                                <label className="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                    Phone
                                </label>

                                <input
                                    type="text"
                                    value={location.phone || ""}
                                    onChange={(e) =>
                                        updateLocation(
                                            index,
                                            "phone",
                                            e.target.value,
                                        )
                                    }
                                    className="
                                        w-full
                                        rounded-xl
                                        border border-white/10
                                        bg-black/20
                                        px-4
                                        py-3
                                        text-white
                                    "
                                />
                            </div>

                            {/* EMAIL */}

                            <div>
                                <label className="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    value={location.email || ""}
                                    onChange={(e) =>
                                        updateLocation(
                                            index,
                                            "email",
                                            e.target.value,
                                        )
                                    }
                                    className="
                                        w-full
                                        rounded-xl
                                        border border-white/10
                                        bg-black/20
                                        px-4
                                        py-3
                                        text-white
                                    "
                                />
                            </div>

                            {/* ADDRESS */}

                            <div className="md:col-span-2">
                                <label className="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                    Address
                                </label>

                                <textarea
                                    rows="4"
                                    value={location.address || ""}
                                    onChange={(e) =>
                                        updateLocation(
                                            index,
                                            "address",
                                            e.target.value,
                                        )
                                    }
                                    className="
                                        w-full
                                        rounded-xl
                                        border border-white/10
                                        bg-black/20
                                        px-4
                                        py-3
                                        text-white
                                    "
                                />
                            </div>
                        </div>
                    </div>
                ))}
            </div>

            {/* SAVE */}

            <div className="mt-8">
                <button
                    type="button"
                    onClick={saveLocations}
                    className="
                        px-8
                        py-4
                        rounded-full
                        bg-yellow-500
                        text-black
                        font-black
                        uppercase
                        tracking-widest
                    "
                >
                    Save Locations
                </button>
            </div>
        </div>
    );
}
