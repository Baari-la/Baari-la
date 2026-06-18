import CompanySectionHeader from "./CompanySectionHeader";

export default function MachinesSection({ data, setData }) {
    const machineCategories = [
        "spinning",
        "knitting",
        "weaving",
        "dyeing",
        "printing",
        "finishing",
        "embroidery",
        "garment",
        "nonwoven",
        "technical_textile",
        "recycling",
    ];

    const createEmptyMachine = () => ({
        machine_category: "",
        machine_type: "",

        machine_brand: "",
        machine_model: "",

        quantity: 1,

        production_capacity: "",
        capacity_unit: "kg/day",

        energy_consumption: "",
        energy_unit: "kwh/hour",

        working_width: "",
        gauge_specification: "",

        year_installed: "",

        machine_condition: "good",

        automation_level: "automatic",

        country_origin: "",

        is_active: true,

        notes: "",
    });

    const addMachine = () => {
        setData("machines", [...(data.machines || []), createEmptyMachine()]);
    };

    const removeMachine = (index) => {
        setData(
            "machines",
            (data.machines || []).filter((_, i) => i !== index),
        );
    };

    const updateMachine = (index, field, value) => {
        const updated = [...(data.machines || [])];

        updated[index][field] = value;

        setData("machines", updated);
    };

    return (
        <div className="mb-10">
            <CompanySectionHeader
                title="Machinery Fleet"
                subtitle="Production machines and equipment inventory"
                button={
                    <button
                        type="button"
                        onClick={addMachine}
                        className=" bg-yellow-500 hover:bg-yellow-400 text-[#0a192f] px-4 py-2 rounded-xl text-[10px]
                            font-black uppercase tracking-wider transition"
                    >
                        + Add Machine
                    </button>
                }
            />

            {(data.machines || []).map((machine, index) => (
                <div
                    key={index}
                    className=" bg-slate-900 border
            border-slate-700 rounded-2xl p-6 mb-4"
                >
                    {/* HEADER */}

                    <div className="flex justify-between items-center mb-6">
                        <div>
                            <h4 className="text-white font-bold">
                                Machine #{index + 1}
                            </h4>

                            <p className="text-xs text-slate-400">
                                {machine.machine_type || "New Machine"}
                            </p>
                        </div>
                    </div>

                    {/* ROW 1 */}
                    <p className="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-3">
                        Basic Information
                    </p>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {/* CATEGORY */}
                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Category
                            </label>

                            <select
                                value={machine.machine_category || ""}
                                onChange={(e) =>
                                    updateMachine(
                                        index,
                                        "machine_category",
                                        e.target.value,
                                    )
                                }
                                className="w-full bg-[#0a192f] border border-white/10 rounded-2xl p-3 text-white"
                            >
                                <option value="">Select Category</option>

                                {machineCategories.map((category) => (
                                    <option key={category} value={category}>
                                        {category
                                            .replace("_", " ")
                                            .replace(/\b\w/g, (l) =>
                                                l.toUpperCase(),
                                            )}
                                    </option>
                                ))}
                            </select>
                        </div>

                        {/* TYPE */}
                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Machine Type
                            </label>

                            <input
                                type="text"
                                value={machine.machine_type || ""}
                                onChange={(e) => {
                                    const updated = [...(data.machines || [])];
                                    updated[index].machine_type =
                                        e.target.value;
                                    setData("machines", updated);
                                }}
                                placeholder="Air Jet Loom"
                                className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                            />
                        </div>

                        {/* QTY */}
                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Quantity
                            </label>

                            <input
                                type="number"
                                value={machine.quantity ?? ""}
                                onChange={(e) => {
                                    const updated = [...(data.machines || [])];
                                    updated[index].quantity = parseInt(
                                        e.target.value || 0,
                                    );
                                    setData("machines", updated);
                                }}
                                className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                            />
                        </div>
                    </div>

                    {/* ROW 2 */}
                    {/* MACHINE SPECIFICATION */}
                    <p className="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mt-6 mb-3">
                        Machine Specification
                    </p>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {/* BRAND */}

                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Brand
                            </label>

                            <input
                                type="text"
                                value={machine.machine_brand || ""}
                                onChange={(e) => {
                                    const updated = [...(data.machines || [])];

                                    updated[index].machine_brand =
                                        e.target.value;

                                    setData("machines", updated);
                                }}
                                placeholder="Picanol"
                                className="
                w-full
                bg-white/5
                border
                border-white/10
                rounded-2xl
                text-white
                p-3
            "
                            />
                        </div>

                        {/* MODEL */}

                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Model
                            </label>

                            <input
                                type="text"
                                value={machine.machine_model || ""}
                                onChange={(e) => {
                                    const updated = [...(data.machines || [])];

                                    updated[index].machine_model =
                                        e.target.value;

                                    setData("machines", updated);
                                }}
                                placeholder="Optimax-i"
                                className="
                w-full
                bg-white/5
                border
                border-white/10
                rounded-2xl
                text-white
                p-3
            "
                            />
                        </div>

                        {/* COUNTRY ORIGIN */}

                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Country Origin
                            </label>

                            <input
                                type="text"
                                value={machine.country_origin || ""}
                                onChange={(e) => {
                                    const updated = [...(data.machines || [])];

                                    updated[index].country_origin =
                                        e.target.value;

                                    setData("machines", updated);
                                }}
                                placeholder="Belgium"
                                className="w-full bg-white/5
                border border-white/10 rounded-2xl text-white p-3"
                            />
                        </div>
                    </div>

                    {/* ROW 3 */}
                    {/* PRODUCTION & INSTALLATION */}

                    <p className="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mt-6 mb-3">
                        Production & Installation
                    </p>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {/* PRODUCTION CAPACITY */}

                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Production Capacity
                            </label>

                            <input
                                type="number"
                                value={machine.production_capacity ?? ""}
                                onChange={(e) => {
                                    const updated = [...(data.machines || [])];

                                    updated[index].production_capacity = Number(
                                        e.target.value,
                                    );

                                    setData("machines", updated);
                                }}
                                placeholder="5000"
                                className="
                w-full
                bg-white/5
                border
                border-white/10
                rounded-2xl
                text-white
                p-3
            "
                            />
                        </div>

                        {/* CAPACITY UNIT */}

                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Capacity Unit
                            </label>

                            <select
                                value={machine.capacity_unit || "kg/day"}
                                onChange={(e) => {
                                    const updated = [...(data.machines || [])];

                                    updated[index].capacity_unit =
                                        e.target.value;

                                    setData("machines", updated);
                                }}
                                className="
                w-full
                bg-[#0a192f]
                border
                border-white/10
                rounded-2xl
                p-3
                text-white
            "
                            >
                                <option value="kg/day">kg/day</option>
                                <option value="meter/day">meter/day</option>
                                <option value="yard/day">yard/day</option>
                                <option value="piece/day">piece/day</option>
                                <option value="roll/day">roll/day</option>
                                <option value="ton/day">ton/day</option>
                            </select>
                        </div>

                        {/* YEAR INSTALLED */}

                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Year Installed
                            </label>

                            <input
                                type="number"
                                min="1900"
                                max="2100"
                                value={machine.year_installed ?? ""}
                                onChange={(e) => {
                                    const updated = [...(data.machines || [])];

                                    updated[index].year_installed =
                                        e.target.value;

                                    setData("machines", updated);
                                }}
                                placeholder="2024"
                                className="
                w-full
                bg-white/5
                border
                border-white/10
                rounded-2xl
                text-white
                p-3
            "
                            />
                        </div>
                    </div>

                    {/* Row 4 */}

                    <p className="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mt-6 mb-3">
                        Technical Specification
                    </p>

                    <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                        {/* WORKING WIDTH */}

                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Working Width
                            </label>

                            <input
                                type="text"
                                value={machine.working_width || ""}
                                onChange={(e) => {
                                    const updated = [...(data.machines || [])];

                                    updated[index].working_width =
                                        e.target.value;

                                    setData("machines", updated);
                                }}
                                placeholder="340 cm"
                                className="
                w-full
                bg-white/5
                border
                border-white/10
                rounded-2xl
                text-white
                p-3
            "
                            />
                        </div>

                        {/* GAUGE */}

                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Gauge Specification
                            </label>

                            <input
                                type="text"
                                value={machine.gauge_specification || ""}
                                onChange={(e) => {
                                    const updated = [...(data.machines || [])];

                                    updated[index].gauge_specification =
                                        e.target.value;

                                    setData("machines", updated);
                                }}
                                placeholder="28G"
                                className="
                w-full
                bg-white/5
                border
                border-white/10
                rounded-2xl
                text-white
                p-3
            "
                            />
                        </div>

                        {/* CONDITION */}

                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Machine Condition
                            </label>

                            <select
                                value={machine.machine_condition || "good"}
                                onChange={(e) => {
                                    const updated = [...(data.machines || [])];

                                    updated[index].machine_condition =
                                        e.target.value;

                                    setData("machines", updated);
                                }}
                                className="
                w-full
                bg-[#0a192f]
                border
                border-white/10
                rounded-2xl
                p-3
                text-white
            "
                            >
                                <option value="excellent">Excellent</option>

                                <option value="good">Good</option>

                                <option value="fair">Fair</option>

                                <option value="needs_repair">
                                    Needs Repair
                                </option>
                            </select>
                        </div>

                        {/* AUTOMATION */}

                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Automation Level
                            </label>

                            <select
                                value={machine.automation_level || "automatic"}
                                onChange={(e) => {
                                    const updated = [...(data.machines || [])];

                                    updated[index].automation_level =
                                        e.target.value;

                                    setData("machines", updated);
                                }}
                                className="
                w-full
                bg-[#0a192f]
                border
                border-white/10
                rounded-2xl
                p-3
                text-white
            "
                            >
                                <option value="manual">Manual</option>

                                <option value="semi_automatic">
                                    Semi Automatic
                                </option>

                                <option value="automatic">Automatic</option>
                            </select>
                        </div>
                    </div>
                    {/* ROW 5 */}
                    {/* PRODUCTION EFFICIENCY */}

                    <p className="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mt-6 mb-3">
                        Production Efficiency
                    </p>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Energy Consumption
                            </label>

                            <input
                                type="number"
                                value={machine.energy_consumption ?? ""}
                                onChange={(e) =>
                                    updateMachine(
                                        index,
                                        "energy_consumption",
                                        Number(e.target.value),
                                    )
                                }
                                placeholder="125"
                                className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                            />
                        </div>

                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Energy Unit
                            </label>

                            <select
                                value={machine.energy_unit || "kwh/hour"}
                                onChange={(e) =>
                                    updateMachine(
                                        index,
                                        "energy_unit",
                                        e.target.value,
                                    )
                                }
                                className="w-full bg-[#0a192f] border border-white/10 rounded-2xl p-3 text-white"
                            >
                                <option value="kwh/hour">kWh/hour</option>

                                <option value="kwh/day">kWh/day</option>

                                <option value="kwh/kg">kWh/kg</option>

                                <option value="kwh/meter">kWh/meter</option>
                            </select>
                        </div>
                    </div>
                    {/* NOTES & REMARKS */}

                    <p className="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mt-6 mb-3">
                        Notes & Remarks
                    </p>

                    <div className="space-y-4">
                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Technical Notes
                            </label>

                            <textarea
                                rows="4"
                                value={machine.notes || ""}
                                onChange={(e) => {
                                    const updated = [...(data.machines || [])];

                                    updated[index].notes = e.target.value;

                                    setData("machines", updated);
                                }}
                                placeholder="Additional machine information, upgrades, certifications, special capabilities, maintenance records, etc."
                                className="
                w-full
                bg-white/5
                border
                border-white/10
                rounded-2xl
                text-white
                p-4
                resize-none
                focus:border-yellow-500/50
                focus:ring-2
                focus:ring-yellow-500/20
            "
                            />
                        </div>
                    </div>

                    {/* Tombol */}
                    <div className="flex justify-end gap-3 mt-6 pt-6 border-t border-white/10">
                        <button
                            type="button"
                            onClick={() => removeMachine(index)}
                            className="
            bg-red-500/20
            hover:bg-red-500
            text-red-400
            hover:text-white
            px-5
            py-3
            rounded-2xl
            text-xs
            font-black
            uppercase
            tracking-wider
            transition-all
        "
                        >
                            <i className="fas fa-trash mr-2"></i>
                            Remove Machine
                        </button>
                    </div>
                </div>
            ))}
        </div>
    );
}
