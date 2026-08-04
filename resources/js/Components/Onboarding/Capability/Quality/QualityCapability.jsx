import LaboratorySection from "./LaboratorySection";
import AccreditationSection from "./AccreditationSection";
import CertificationSection from "./CertificationSection";

export default function QualityCapability({ business, data, setData }) {
    return (
        <div className="space-y-10">
            {/* ======================================================
             | Laboratory Capability™
             ====================================================== */}

            <LaboratorySection
                business={business}
                data={data}
                setData={setData}
            />

            {/* ======================================================
             | Accreditation & Recognition™
             ====================================================== */}

            <AccreditationSection
                business={business}
                data={data}
                setData={setData}
            />

            {/* ======================================================
             | Certification Services™
             ====================================================== */}

            <CertificationSection
                business={business}
                data={data}
                setData={setData}
            />
        </div>
    );
}
