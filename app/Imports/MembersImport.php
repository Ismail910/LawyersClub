<?php

namespace App\Imports;

use App\Models\Member;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // For logging

class MembersImport implements ToCollection, WithHeadingRow
{
    // Helper function to convert Excel serial date to Y-m-d format
    protected function excelToDate($excelDate)
    {
        // If it's a valid numeric date (e.g., Excel's date format), convert it to Y-m-d
        if (is_numeric($excelDate)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($excelDate)->format('Y-m-d');
        }

        // Otherwise, return null if it's not a valid date
        return null;
    }

    // Helper function to map 'نعم' (Yes) and 'لا' (No) to boolean values
    protected function mapVotingRight($value)
    {
        // If value is "نعم" (Yes), return true (1), if it's "لا" (No), return false (0)
        return $value === 'نعم' ? 1 : ($value === 'لا' ? 0 : null);
    }

    // Helper function to convert empty strings to null
    protected function emptyStringToNull($value)
    {
        return $value === '' ? null : $value;
    }

    // Helper function to extract the year from a string
    protected function extractYear($value)
    {
        // Remove any non-numeric characters
        $value = preg_replace('/\D/', '', $value); // Remove non-numeric characters

        // Ensure the value is a valid 4-digit year
        if (strlen($value) === 4 && is_numeric($value)) {
            return $value; // Return the 4-digit year
        }

        // Return null if the extracted value is not a valid year
        return null;
    }

    // Process each row from the uploaded file
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Log the raw row data to see what is being read
            Log::info('Row Data:', $row->toArray());

            // Prepare the data from each row
            $data = [
                'name' => $this->emptyStringToNull($row['asm_alaado'] ?? null),
                'job_title' => $this->emptyStringToNull($row['alothyf'] ?? null),
                'employee_code' => $this->emptyStringToNull($row['rkm_ksym_alsdad'] ?? null),
                'membership_number' => $this->emptyStringToNull($row['rkm_alaadoy'] ?? null),
                'membership_date' => $this->excelToDate($row['tarykh_alaadoy']),
                'address' => $this->emptyStringToNull($row['aanoan_alskn'] ?? null),
                'phone' => $this->emptyStringToNull($row['rkm_altlyfon'] ?? null),
                'payment_voucher_number' => $this->emptyStringToNull($row['rkm_ksym_alsdad'] ?? null),
                // Use the extractYear function to handle the last_payment_year
                'last_payment_year' => $this->extractYear($row['akhr_aaam_sdad'] ?? ''),
                'printing_status' => $this->emptyStringToNull($row['hal_altbaa'] ?? null),
                'notes' => $this->emptyStringToNull($row['mlahthat_hal_altbaa'] ?? null),
                'printing_and_payment_date' => $this->excelToDate($row['tarykh_altbaa_oalsdad']),
                'payment_date' => $this->excelToDate($row['tarykh_alsdad']),
                'current_year_paid' => $this->emptyStringToNull($row['msdd_alaaam_alhaly'] ?? null),
                'voting_right' => $this->mapVotingRight($row['hk_alantkhab']),
                'gender' => $this->emptyStringToNull($row['kod_aaam'] ?? null),
            ];

           
            try {
                Member::create($data);
            } catch (\Exception $e) {
                Log::error('Error inserting data into database:', [
                    'error_message' => $e->getMessage(),
                    'data' => $data
                ]);
                dd('Error inserting data into database', $e->getMessage(), $data);
            }
        }
    }
}
